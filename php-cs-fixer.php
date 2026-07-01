<?php

$finder = PhpCsFixer\Finder::create()
->in(__DIR__ . '/build')
->in(__DIR__ . '/core')
->name('*.php');

return (new PhpCsFixer\Config())
->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => true,
    'blank_line_before_statement' => true,
    'cast_spaces' => true,
    'concat_space' => ['spacing' => 'one'],
    'declare_strict_types' => true,
    'function_declaration' => true,
    'method_argument_space' => true,
    'no_unused_imports' => true,
    'ordered_imports' => true,
    'phpdoc_align' => true,
    'phpdoc_separation' => true,
    'phpdoc_summary' => true,
    'single_quote' => true,
    'trailing_comma_in_multiline' => true,
])
->setFinder($finder);
