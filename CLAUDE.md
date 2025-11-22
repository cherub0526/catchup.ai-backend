# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a video assistant API built with **Hypervel**, a Laravel-style PHP framework with native coroutine support for ultra-high performance. The application provides AI-powered video analysis, summaries, captions, and chat capabilities for YouTube content, with Paddle subscription management.

**Core functionality:**
- RSS feed subscription and synchronization for YouTube channels
- Video caption extraction and AI-powered analysis
- AI-generated video summaries using OpenAI
- Interactive chat with video content
- Paddle subscription management with webhook handling
- OAuth authentication (local, Facebook, Google)

## Framework-Specific Notes

Hypervel is built on top of Hyperf and provides Laravel-like APIs with coroutine support. Key differences from standard Laravel:

- Uses Swoole for coroutine-based concurrency
- HTTP responses use PSR-7 interfaces: `\Psr\Http\Message\ResponseInterface`
- Most Laravel patterns work identically (Eloquent, routing, validation, etc.)
- Queue system supports coroutine drivers for non-blocking I/O operations
- Framework components are in `Hypervel\*` namespaces (vs Laravel's `Illuminate\*`)

## Development Commands

### Server Management
```bash
# Start the development server (blocking, keeps running)
composer start
# or
php artisan start

# Start with file watching (auto-reload on code changes)
php artisan server:watch
```

### Database
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration (drop all tables and re-migrate)
php artisan migrate:fresh

# Seed database
php artisan db:seed
```

### Queue Workers
```bash
# Start queue worker
php artisan queue:work

# Process specific queue
php artisan queue:work --queue=default
```

### Testing
```bash
# Run all tests
composer test
# or
php artisan test
# or
vendor/bin/phpunit

# Run specific test file
vendor/bin/phpunit tests/Feature/ExampleTest.php

# Run specific test method
vendor/bin/phpunit --filter testExample
```

### Code Quality
```bash
# Fix code style for modified files (vs origin/main)
composer cs-diff

# Fix code style for specific file
composer cs-fix app/Models/User.php

# Run static analysis
composer analyse
```

### RSS Synchronization
```bash
# Sync all RSS feeds
php artisan rss:sync

# Sync specific RSS feed by ID
php artisan rss:sync --id=1
```

## Architecture Overview

### Application Structure

**Models & Database:**
- `User` - Users with social auth support (local/Facebook/Google), linked to Paddle customers
- `Media` - YouTube videos with status tracking (created → progress → ready)
- `Caption` - Video captions/transcripts
- `Summary` - AI-generated video summaries
- `Rss` - YouTube RSS feed subscriptions
- `RssSyncHistory` - RSS sync operation tracking
- `Oauth` - OAuth authentication tokens
- `Plan`, `Price`, `Paddle` - Paddle subscription management models
- `Userable` pivot table - Links users to both RSS feeds and media (polymorphic-style many-to-many)

**Controllers (API V1):**
- `AuthController` - JWT authentication (login, register, refresh, logout)
- `OauthController` - Social OAuth (Facebook, Google)
- `UsersController` - User profile management
- `MediaController` - Media listing and retrieval
- `Media/CaptionsController` - Caption management for videos
- `Media/SummariesController` - Summary management for videos
- `Media/ChatController` - AI chat interface for video content
- `RSSController` - RSS feed subscription management
- `SubscriptionsController` - Subscription usage tracking and management
- `Subscriptions/PlansController` - Available subscription plans
- `Subscriptions/WebhookController` - Paddle webhook handling
- `Webhook/YoutubeController` - YouTube webhook notifications

**Services:**
- `PaddleClient` - Wrapper for Paddle SDK (customers, products, prices, subscriptions, transactions)
- `RssFeedAsapService` - RSS feed parsing and processing
- `Prompts/*` - Template system for OpenAI prompts:
  - `TemplateCompletionManager` - Manages OpenAI API calls with templates
  - `AnalysisTemplate`, `SummaryTemplate`, `TranslationTemplate`, `CaptionTemplate`, `AssistantTemplate` - Specific prompt templates
  - Uses Template pattern with `TemplateInterface` and `BaseTemplate`

**Jobs:**
- `Rss/SyncJob` - Processes RSS feeds, creates Media records, syncs with users

**Validators:**
- Custom validator classes (e.g., `AuthValidator`, `MediaValidator`) using `BaseValidator`
- Validation methods: `setIndexRules()`, `setStoreRules()`, etc.
- Use `InvalidRequestException` for validation failures

**Resources:**
- API Resources for JSON responses: `UserResource`, `MediaResource`, `CaptionResource`, `SummaryResource`, `PlanResource`, `PriceResource`

**Observers:**
- `PlanObserver`, `PriceObserver` - Sync with Paddle when plans/prices change
- `UserObserver` - Handle user lifecycle events

### Key Patterns

**Authentication:**
- JWT-based authentication with `auth('jwt')` guard
- Token format: Bearer token with configurable TTL (config `jwt.ttl` in minutes)
- OAuth social login support (Facebook, Google) via Hypervel Socialite

**Validation:**
- Custom Validator classes extending `BaseValidator`
- Throw `InvalidRequestException` with error array on validation failure
- Rule methods like `setStoreRules()`, `setIndexRules()` define validation per action

**API Responses:**
- Use `response()->json()` for JSON responses
- Use API Resources for structured output
- Controllers extend `AbstractController` with helper methods

**Queue System:**
- Default driver: database (configurable via `QUEUE_CONNECTION`)
- Jobs implement `ShouldQueue` interface and use `Queueable` trait
- Supports coroutine-based queue drivers for concurrent processing
- Dispatch with: `YourJob::dispatch($params)`

**AI Integration:**
- OpenAI completion via `App\Utils\OpenAI\Completion`
- Template-based prompt system for consistent AI interactions
- Templates define system messages and user message structure
- `TemplateCompletionManager` handles API calls and response extraction

**Paddle Integration:**
- Webhook verification using `Paddle\SDK\Notifications\Verifier` and `Secret`
- Webhook secret: `PADDLE_WEBHOOK_SECRET_KEY` environment variable
- Sandbox mode: controlled by `PADDLE_SANDBOX` environment variable
- Store Paddle entities with `foreign_type` and `foreign_id` polymorphic pattern

### RSS Feed Processing

1. User subscribes to YouTube RSS feed
2. `RssSyncJob` is dispatched (manually via command or scheduled)
3. Job parses RSS XML, extracts video metadata
4. Creates `Media` records for new videos not in database
5. Links media to all users subscribed to that RSS feed via `userables` pivot table
6. Tracks sync operations in `RssSyncHistory`

### Routing

- API routes defined in `routes/v1.php` (versioned)
- Web routes in `routes/web.php` (minimal, mostly for testing)
- Route groups use array syntax: `Route::group('/path', function() { ... }, ['as' => 'name'])`
- Middleware applied via route config: `['middleware' => ['auth']]`

## Environment Configuration

Required environment variables:
- `APP_KEY` - Application encryption key
- `DB_*` - Database connection settings
- `JWT_SECRET` - JWT signing secret
- `PADDLE_API_KEY` - Paddle API key
- `PADDLE_WEBHOOK_SECRET_KEY` - Paddle webhook verification secret
- `PADDLE_SANDBOX` - Boolean for sandbox mode
- `OPENAI_API_KEY` - OpenAI API key (used in `App\Utils\OpenAI\Completion`)
- OAuth credentials: `FACEBOOK_CLIENT_ID`, `FACEBOOK_CLIENT_SECRET`, `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`

## Testing Notes

- Test environment uses SQLite (`DB_CONNECTION=sqlite_testing`)
- Tests located in `tests/Feature/` and `tests/Unit/`
- Bootstrap file: `tests/bootstrap.php`
- Helper functions in `tests/helpers.php`
- Use `RefreshDatabase` trait for database tests (see `tests/Feature/RefreshDatabaseTest.php`)

## Important Conventions

**Migrations:**
- Extend `App\Utils\BaseMigration` (not `Illuminate\Database\Migrations\Migration`)
- Use standard Laravel migration methods

**Models:**
- Extend `App\Models\Model` (base model with common configuration)
- User model extends `Hypervel\Foundation\Auth\User` for authentication
- Use `SoftDeletes` trait where applicable
- Define fillable fields, casts, and relationships explicitly

**Controllers:**
- Extend `AbstractController` for common response methods
- Use type hints for Request: `Hypervel\Http\Request`
- Return types: `\Psr\Http\Message\ResponseInterface` for PSR-7 responses

**Error Handling:**
- Throw `InvalidRequestException` for validation errors with array of messages
- Exception handler in `App\Exceptions\Handler`

**Code Style:**
- Uses `declare(strict_types=1);` at top of all PHP files
- PHP CS Fixer configuration in `.php-cs-fixer.php`
- PHPStan configuration in `phpstan.neon` with 300M memory limit
