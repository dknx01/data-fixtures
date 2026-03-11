<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Unit\Purger\Driver;

use Dknx01\DataFixtures\Purger\Configuration;
use Dknx01\DataFixtures\Tests\Helper\TestDriver;
use InvalidArgumentException;
use PDO;
use PHPUnit\Framework\TestCase;

class AbstractDriverTest extends TestCase
{
    public function testDriver(): void
    {
        $config = new Configuration(pdo: new PDO('sqlite::memory:'), dsn: 'foo://127.0.0.1;dbname=bar;charset=utf8mb4');

        $driver = new TestDriver($config);

        $this->assertEquals('sqlite', $driver->getDriver());
        $this->assertEmpty($driver->listTables());
        $this->assertEquals('bar', $driver->getSchemaName());
    }

    public function testDriverWithInvalidSchema(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $config = new Configuration(pdo: new PDO('sqlite::memory:'), dsn: 'foo://127.0.0.1;database=bar;charset=utf8mb4');

        new TestDriver($config);
    }
}
