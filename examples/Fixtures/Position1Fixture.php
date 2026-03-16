<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace examples\Fixtures;

use Dknx01\DataFixtures\Attributes\OrderedFixture;
use Dknx01\DataFixtures\Contract\FixtureInterface;
use PDO;

use const PHP_EOL;

#[OrderedFixture(1)]
class Position1Fixture implements FixtureInterface
{
    public function load(PDO $pdo): void
    {
        echo 'I should be on position 1 in the stack.'.PHP_EOL.'File: '.__FILE__.PHP_EOL;
    }
}
