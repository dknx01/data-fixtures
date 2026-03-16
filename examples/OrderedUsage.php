<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace examples;

use Dknx01\DataFixtures\Attributes\DataFixture;
use Dknx01\DataFixtures\Fixture\DataFixtureTrait;
use Dknx01\DataFixtures\Fixture\FixtureHandler;
use Dknx01\DataFixtures\Purger\Configuration;
use examples\Fixtures\Position1Fixture;
use examples\Fixtures\Simple2Fixture;
use examples\Fixtures\SimpleFixture;
use PDO;

class OrderedUsage
{
    use DataFixtureTrait;

    #[DataFixture(Position1Fixture::class)]
    #[DataFixture(SimpleFixture::class)]
    #[DataFixture(Simple2Fixture::class)]
    public function run(): void
    {
        $this->loadFixtures();
        new FixtureHandler(new Configuration(pdo: new PDO('sqlite::memory:')))->handle($this->fixtures);
    }
}
