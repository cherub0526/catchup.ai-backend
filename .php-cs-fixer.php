<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfig;

$maxProcesses = function_exists('swoole_cpu_num') ? swoole_cpu_num() : 4;

return (new Config())
    ->setParallelConfig(new ParallelConfig($maxProcesses, 10))
    ->setRiskyAllowed(true)
    ->setRules([
        // Base rule sets
        '@PSR12'              => true,
        '@Symfony'            => true,
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer'         => true,

        // --- Formatting ---
        'array_syntax' => ['syntax' => 'short'],
        'list_syntax'  => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],

        // Align "=>"
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align_single_space_minimal',
            ],
        ],

        // Import rules
        'global_namespace_import' => [
            'import_classes'   => true,
            'import_constants' => true,
            'import_functions' => false,
        ],
        'ordered_imports' => [
            'imports_order'  => ['class', 'function', 'const'],
            'sort_algorithm' => 'length',
        ],
        'no_unused_imports' => true,

        // Blank lines
        'blank_line_before_statement' => [
            'statements' => ['declare'],
        ],
        'linebreak_after_opening_tag' => true,

        // PHPDoc
        'phpdoc_align'                     => ['align' => 'left'],
        'phpdoc_no_alias_tag'              => false,
        'phpdoc_separation'                => false,
        'general_phpdoc_annotation_remove' => [
            'annotations' => ['author'],
        ],
        'phpdoc_to_comment' => ['ignored_tags' => ['var']],

        // Class / code ordering
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'ordered_class_elements'      => [
            'order' => [
                'use_trait',
                'case',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
            ],
        ],

        // Language-level behaviors
        'yoda_style' => [
            'equal'                => false,
            'identical'            => false,
            'always_move_variable' => false,
        ],
        'single_quote'                           => true,
        'standardize_not_equals'                 => true,
        'multiline_comment_opening_closing'      => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'constant_case'              => ['case' => 'lower'],
        'lowercase_static_reference' => true,
        'no_useless_else'            => true,

        // PHP 8.3 improvements
        'nullable_type_declaration_for_default_null_value' => true,

        // Misc
        'single_line_comment_style'  => ['comment_types' => []],
        'combine_consecutive_unsets' => true,
        'declare_strict_types'       => true,
        'single_line_empty_body'     => false,
    ])
    ->setFinder(
        Finder::create()
            ->exclude(['public', 'runtime', 'storage', 'vendor'])
            ->in(__DIR__)
    )
    ->setUsingCache(false);
