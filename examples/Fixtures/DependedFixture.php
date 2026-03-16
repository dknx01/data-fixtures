<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace examples\Fixtures;

use Dknx01\DataFixtures\Attributes\DependFixture;
use Dknx01\DataFixtures\Contract\FixtureInterface;
use PDO;

use const PHP_EOL;

#[DependFixture(DependedBaseFixture::class)]
class DependedFixture implements FixtureInterface
{
    public function load(PDO $pdo): void
    {
        echo 'Someone depends on me.'.PHP_EOL.'File: '.__FILE__.PHP_EOL;
    }
}
