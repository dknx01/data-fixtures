<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Unit\Faker;

use Dknx01\DataFixtures\Faker\FakerTrait;
use Dknx01\DataFixtures\Tests\Helper\DummyProvider;
use Faker\Provider\Base;
use PHPUnit\Framework\TestCase;

class FakerTraitTest extends TestCase
{
    use FakerTrait;

    public function testFakerTrait(): void
    {
        self::prepareFaker(['locale' => 'de_DE', 'providers' => [DummyProvider::class]]);

        $faker = self::getFaker();

        $this->assertCount(1, array_filter($faker->getProviders(), static fn (Base $f) => $f instanceof DummyProvider));

        self::createFaker();
    }
}
