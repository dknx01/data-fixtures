<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Helper;

use Dknx01\DataFixtures\Contract\FakerAware;
use Dknx01\DataFixtures\Fixture\FixtureInterface;
use Faker\Generator;
use PDO;

class FakerAwareFixture implements FixtureInterface, FakerAware
{
    public function setFaker(Generator $faker): void
    {
        // do nothing
    }

    public function load(PDO $pdo): void
    {
    }
}
