<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@PSR12' => true,
    'no_unused_imports' => true,
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'braces' => ['allow_single_line_closure' => true],
    'compact_nullable_typehint' => true,
    'concat_space' => ['spacing' => 'one'],
    'declare_equal_normalize' => ['space' => 'single'],
    'function_typehint_space' => true,
    'new_with_braces' => true,
    'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
    'no_extra_blank_lines' => ['tokens' => ['extra']],
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_whitespace_in_blank_line' => true,
    'return_type_declaration' => ['space_before' => 'none'],
    'single_trait_insert_per_statement' => true,
];

$finder = Finder::create()
    ->in([__DIR__ . '/app', __DIR__ . '/tests'])
    ->exclude('Views')
    ->name('*.php');

return (new Config())
    ->setRules($rules)
    ->setFinder($finder)
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setUsingCache(true);
