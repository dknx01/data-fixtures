<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Unit\Fixture;

use Dknx01\DataFixtures\Tests\Helper\FixtureTestCaseDummy;
use Dknx01\DataFixtures\Tests\Helper\OrderedFixture;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DataFixtureTraitTest extends TestCase
{
    use ProphecyTrait;

    public function testLoading(): void
    {
        $testCaseDummy = new FixtureTestCaseDummy();

        $testCaseDummy->dummyTestMethod();
        $fixtures = $testCaseDummy->getFixtures();
        $this->assertCount(4, $fixtures);
        $this->assertEquals(OrderedFixture::class, $fixtures->toArray()[0]::class);
    }
}
