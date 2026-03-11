<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Helper;

use Dknx01\DataFixtures\Fixture\FixtureInterface;
use PDO;

class SimpleFixture implements FixtureInterface
{
    public function load(PDO $pdo): void
    {
    }
}
