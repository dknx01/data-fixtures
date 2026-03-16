<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace examples\Fixtures;

use Dknx01\DataFixtures\Contract\FixtureInterface;
use PDO;

use const PHP_EOL;

class Simple2Fixture implements FixtureInterface
{
    public function load(PDO $pdo): void
    {
        echo 'A second simple fixture loading class.'.PHP_EOL.'File: '.__FILE__.PHP_EOL;
    }
}
