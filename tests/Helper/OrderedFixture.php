<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Helper;

use Dknx01\DataFixtures\Attributes\DependFixture;
use Dknx01\DataFixtures\Fixture\FixtureInterface;
use PDO;

#[\Dknx01\DataFixtures\Attributes\OrderedFixture(0)]
#[DependFixture(DependingOnFixture::class)]
class OrderedFixture implements FixtureInterface
{
    public function load(PDO $pdo): void
    {
    }
}
