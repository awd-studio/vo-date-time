<?php

declare(strict_types=1);

if (!file_exists(__DIR__ . '/../../src')) {
    exit(1);
}

$config = new PhpCsFixer\Config();
$finder = new PhpCsFixer\Finder();

$finder
    ->in(__DIR__ . '/../../src')
    ->append([
        __DIR__ . '/.php-cs-fixer.php',
        __DIR__ . '../rector/*.php',
    ])
;

$config
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PER-CS2.0' => true,
        '@PHP83Migration' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'protected_to_private' => false,
        'native_constant_invocation' => ['strict' => false],
        'nullable_type_declaration_for_default_null_value' => true,
        'no_superfluous_phpdoc_tags' => ['remove_inheritdoc' => true],
        'modernize_strpos' => true,
        'get_class_to_class_keyword' => true,
        'final_class' => true,
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_to_comment' => ['ignored_tags' => ['psalm-suppress']],
        'trailing_comma_in_multiline' => [
            'elements' => ['arguments', 'arrays', 'match', 'parameters'],
            'after_heredoc' => true,
        ],
        'function_declaration' => ['closure_function_spacing' => 'none', 'closure_fn_spacing' => 'none'],
        'no_break_comment' => false,
        'single_line_empty_body' => true,
    ])
    ->setCacheFile(__DIR__ . '/../cache/php-cs-fixer/.php-cs-fixer.cache')
;

return $config;
