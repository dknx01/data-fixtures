<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Unit\Fixture;

use Dknx01\DataFixtures\Fixture\FixtureCollection;
use Dknx01\DataFixtures\Fixture\FixtureInterface;
use OutOfBoundsException;
use PDO;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class FixtureCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $collection = new FixtureCollection();
        $this->assertCount(0, $collection->toArray());

        $collection->add($this->getFixture1());
        $this->assertEquals(1, $collection->count());

        $this->assertTrue($collection->has($this->getFixture1()));

        $fixture2 = $this->getFixture2();
        $collection->addAt(0, $fixture2);
        $this->assertCount(2, $collection->toArray());
        $this->assertSame($fixture2, $collection->toArray()[0]);

        $collection->removeAt(0);
        $this->assertCount(1, $collection->toArray());
    }

    #[TestWith(['position' => -1])]
    #[TestWith(['position' => 3])]
    public function testAddAtWithInvalidPosition(int $position): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Position {$position} is out of bounds (allowed 0‑1).");

        $collection = new FixtureCollection();
        $collection->add($this->getFixture1());
        $collection->addAt($position, $this->getFixture2());
    }

    #[TestWith(['position' => -1])]
    #[TestWith(['position' => 3])]
    public function testRemoveAtWithInvalidPosition(int $position): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Cannot remove position {$position}; valid range is 0‑1");

        $collection = new FixtureCollection();
        $collection->add($this->getFixture1());
        $collection->add($this->getFixture2());
        $collection->removeAt($position);
    }

    private function getFixture1(): FixtureInterface
    {
        return new class implements FixtureInterface {
            public function load(PDO $pdo): void
            {
                // dummy method
            }
        };
    }

    private function getFixture2(): FixtureInterface
    {
        return new class implements FixtureInterface {
            public function load(PDO $pdo): void
            {
                // dummy method
            }
        };
    }
}
