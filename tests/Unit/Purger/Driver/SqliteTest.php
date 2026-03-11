<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Unit\Purger\Driver;

use Dknx01\DataFixtures\Exception\DatabaseQueryException;
use Dknx01\DataFixtures\Purger\Configuration;
use Dknx01\DataFixtures\Purger\Driver\Sqlite;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class SqliteTest extends TestCase
{
    use ProphecyTrait;

    public function testMySql(): void
    {
        $tables = ['table1'];
        $pdo = $this->prophesize(PDO::class);
        $pdo->exec('PRAGMA foreign_keys = OFF')
            ->shouldBeCalledOnce()
            ->willReturn(1);
        $pdo->exec('PRAGMA foreign_keys = ON')
            ->shouldBeCalledOnce()
            ->willReturn(1);
        $pdo->exec('DELETE FROM "table1"')
            ->shouldBeCalledOnce()
            ->willReturn(1);
        $pdo->exec('VACUUM')
            ->shouldBeCalledOnce()
            ->willReturn(1);

        $queryStatement = $this->prophesize(PDOStatement::class);
        $queryStatement->fetchAll(PDO::FETCH_COLUMN)->shouldBeCalledOnce()->willReturn($tables);
        $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")
            ->shouldBeCalledOnce()
            ->willReturn($queryStatement->reveal());

        $driver = new Sqlite(new Configuration(pdo: $pdo->reveal(), databaseName: 'foo'));

        $driver->disableForeignKeys();
        $this->assertArraysAreEqual($tables, $driver->listTables());
        $driver->truncateTable('table1');
        $driver->enableForeignKeys();
        $driver->additionalTasks();
    }

    public function testListTablesWithException(): void
    {
        $this->expectException(DatabaseQueryException::class);
        $this->expectExceptionMessage('SQLite database query failed');

        $pdo = $this->prophesize(PDO::class);
        $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")
            ->shouldBeCalledOnce()
            ->willReturn(false);

        $driver = new Sqlite(new Configuration(pdo: $pdo->reveal(), databaseName: 'foo'));
        $driver->listTables();
    }
}
