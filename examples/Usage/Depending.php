<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace examples\Usage;

use examples\DependingUsage;
use League\CLImate\CLImate;

class Depending
{
    public static function run(CLImate $cli): void
    {
        $cli->flank('Usage', '*');
        $cli->dim(<<<PHP
// The fixture loading class
    #[DataFixture(WithDependingFixture::class)]
    public function foo(): void
    {
        // your code
    }
PHP);
        $cli->br();
        $cli->dim(<<<PHP
// The fixture class
#[DependFixture(DependedFixture::class)]
class WithDependingFixture implements FixtureInterface
{
    // your code
}
PHP);
        $cli->border();
        $cli->info('Executing Depending Fixture');
        new DependingUsage()->run();
    }
}
