<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace examples\Usage;

use examples\OrderedUsage;
use League\CLImate\CLImate;

class Ordered
{
    public static function run(CLImate $cli): void
    {
        $cli->flank('Usage', '*');
        $cli->dim(<<<PHP
// The fixture loading class
    #[DataFixture(\examples\Fixtures\Position1Fixture::class)]
    public function foo(): void
    {
        // your code
    }
PHP);
        $cli->br();
        $cli->dim(<<<PHP
// The fixture class
#[\Dknx01\DataFixtures\Attributes\OrderedFixture(1)]
class Position1Fixture implements FixtureInterface
{
    // your code
}
PHP);
        $cli->border();
        $cli->info('Executing Ordered Fixture');
        new OrderedUsage()->run();
    }
}
