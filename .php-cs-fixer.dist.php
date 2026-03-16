<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

if (!file_exists(__DIR__.'/src')) {
    exit(0);
}

$fileHeaderComment = <<<'EOF'
This file is part of the dknx01/data-fixtures package.
(c) dknx01/data-fixtures
EOF;

return (new PhpCsFixer\Config())
    // @see https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/pull/7777
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRules([
        '@PHPUnit75Migration:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'header_comment' => ['header' => $fileHeaderComment],
        'nullable_type_declaration' => true,
        'native_function_invocation' => ['include' => ['@compiler_optimized', 'sprintf'], 'scope' => 'namespaced', 'strict' => true],
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'match', 'parameters']],
        'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
        'declare_strict_types' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([
                __DIR__.'/src',
                __DIR__.'/tests',
                __DIR__.'/examples',
            ])
            ->append([__FILE__])
    )
    ->setCacheFile('.php-cs-fixer.cache')
;
