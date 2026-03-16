<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace examples\Usage;

use examples\SimpleUsage;
use League\CLImate\CLImate;

class Simple
{
    public static function run(CLImate $cli): void
    {
        $cli->flank('Usage', '*');
        $cli->dim(<<<PHP
// The fixture loading class
    #[DataFixture(SimpleFixture::class)]
    public function foo(): void
    {
        // your code
    }
PHP);
        $cli->border();
        $cli->info('Executing Simple Fixture');
        new SimpleUsage()->run();
    }
}
