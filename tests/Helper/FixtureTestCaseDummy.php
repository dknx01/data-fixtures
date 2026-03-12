<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Helper;

use Dknx01\DataFixtures\Attributes\DataFixture;
use Dknx01\DataFixtures\Fixture\DataFixtureTrait;
use Dknx01\DataFixtures\Fixture\FixtureCollection;

#[DataFixture(SimpleFixture::class)]
class FixtureTestCaseDummy
{
    use DataFixtureTrait {
        // expose the protected method for the test
        loadFixtures as public invokeLoadFixtures;
    }

    #[DataFixture(FakerAwareFixture::class)]
    #[DataFixture(OrderedFixture::class)]
    public function dummyTestMethod(): void
    {
        $this->loadFixtures();
    }

    public function getFixtures(): FixtureCollection
    {
        return $this->fixtures;
    }
}
