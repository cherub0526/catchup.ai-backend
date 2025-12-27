/**
 * AWS Lambda function for transcribing audio files using Groq API.
 *
 * This function is designed to be triggered by an Amazon SQS queue. It processes
 * messages containing a URL to an audio file and a callback URL.
 *
 * Environment Variables:
 *   - GROQ_API_KEY: Your API key for the Groq service.
 *
 * SQS Message Body (JSON format):
 * {
 *   "callback_url": "https://yourapi.com/webhook/transcription-result",
 *   "transcribe": {
 *     "url": "https://example.com/path/to/audio.mp3",
 *     "model": "whisper-large-v3-turbo",
 *     "response_format": "verbose_json",
 *     "temperature": 0,
 *     "timestamp_granularities": ["segment", "word"]
 *   }
 * }
 *
 * Process Flow:
 * 1. Receives a batch of messages from SQS.
 * 2. For each message, it sends the audio URL and parameters to the Groq API via native fetch.
 * 3. Transcribes the audio file using the model specified in the message parameters.
 * 4. Sends the transcription result (or an error status) to the `callback_url`.
 *
 * Error Handling:
 * - If a message is malformed (e.g., invalid JSON, missing URLs), it's logged and skipped without retry.
 * - If any other error occurs during processing (e.g., download fails, Groq API error,
 *   callback fails), the function attempts to notify the callback URL with an 'error'
 *   status. The message is then marked as a failure, allowing SQS to handle retries
 *   based on the queue's redrive policy. This function supports partial batch failures.
 */

const ActionName = "Transcribe";

/**
 * Processes a single SQS record.
 * @param {object} record - The SQS record to process.
 */
const processRecord = async (record) => {
    let body, callback_url, transcribe_params;

    try {
        // Safely parse message body and extract parameters
        try {
            body = JSON.parse(record.body);
            callback_url = body.callback_url;
            transcribe_params = body.transcribe;
        } catch (e) {
            console.error("Skipping record: Invalid JSON in message body.", {
                messageId: record.messageId,
                body: record.body,
            });
            return; // Malformed message, don't retry.
        }

        // Validate the presence of the main objects
        if (!callback_url || !transcribe_params) {
            console.error("Skipping record: Missing callback_url or transcribe object in message body.", {
                messageId: record.messageId,
            });
            return;
        }

        // Validate all required keys in the 'transcribe' object
        const required_keys = ["url", "model", "response_format", "temperature", "timestamp_granularities"];
        const missing_keys = required_keys.filter((key) => !(key in transcribe_params));

        if (missing_keys.length > 0) {
            console.error(`Skipping record: 'transcribe' object missing required keys: ${missing_keys.join(", ")}`, {
                messageId: record.messageId,
            });
            return;
        }

        console.log(`Processing file via URL parameter: ${transcribe_params.url}`);

        // 1. 建立 FormData 並直接使用 URL
        // 注意：若 Groq API 不支援 URL 傳遞，此處可能需要改回下載 Blob 的方式
        console.log("Preparing FormData with file URL...");
        const formData = new FormData();
        formData.append("url", transcribe_params.url);
        formData.append("model", transcribe_params.model);
        formData.append("response_format", transcribe_params.response_format);
        formData.append("temperature", String(transcribe_params.temperature));

        if (Array.isArray(transcribe_params.timestamp_granularities)) {
            transcribe_params.timestamp_granularities.forEach((g) => {
                formData.append("timestamp_granularities[]", g);
            });
        }

        // 2. 使用 fetch 發送請求至 Groq API
        console.log("Starting transcription with Groq API via fetch...");
        const groqResponse = await fetch("https://api.groq.com/openai/v1/audio/transcriptions", {
            method: "POST",
            headers: {
                Authorization: `Bearer ${process.env.GROQ_API_KEY}`,
            },
            body: formData,
        });

        if (!groqResponse.ok) {
            const errorBody = await groqResponse.text();
            throw new Error(`Groq API failed with status ${groqResponse.status}: ${errorBody}`);
        }

        const transcription = await groqResponse.json();
        console.log("Transcription finished.");

        // 3. Send the successful transcription result to the callback URL.
        const postData = JSON.stringify({
            status: "success",
            data: transcription,
        });

        console.log(`Sending transcription to callback URL: ${callback_url}`);
        const callbackResponse = await fetch(callback_url, {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: postData,
        });

        if (!callbackResponse.ok) {
            const errorBody = await callbackResponse.text();
            throw new Error(
                `Callback failed with status ${callbackResponse.status}: ${callbackResponse.statusText}. Response: ${errorBody}`
            );
        }

        console.log("Callback successful.");
    } catch (error) {
        console.error("Error processing message:", {messageId: record.messageId, error: error.message});

        // If an error occurs, try to notify the callback URL with a failure status.
        if (callback_url) {
            try {
                console.log(`Notifying callback URL of failure: ${callback_url}`);
                await fetch(callback_url, {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({
                        status: "error",
                        data: {
                            message: error.message,
                        },
                    }),
                });
            } catch (notifyError) {
                console.error("Failed to notify callback URL of the error:", notifyError);
            }
        }

        // Re-throw the error to mark this message as a failure for SQS partial batch response.
        throw error;
    }
    // No finally block needed as there are no local files to clean up.
};

/**
 * The main Lambda handler function.
 * It processes a batch of SQS records and supports partial batch failure.
 * @param {object} event - The SQS event object.
 */
export const handler = async (event) => {
    const batchItemFailures = [];

    const promises = event.Records.map(async (record) => {
        try {
            await processRecord(record);
        } catch (error) {
            // If processRecord throws an error, add its messageId to the failure list.
            batchItemFailures.push({itemIdentifier: record.messageId});
        }
    });

    // Wait for all records in the batch to be processed.
    await Promise.all(promises);

    // Return the list of failed message IDs to SQS.
    return {batchItemFailures};
};
