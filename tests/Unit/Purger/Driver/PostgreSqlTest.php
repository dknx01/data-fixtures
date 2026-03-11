<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Unit\Purger\Driver;

use Dknx01\DataFixtures\Exception\DatabaseQueryException;
use Dknx01\DataFixtures\Purger\Configuration;
use Dknx01\DataFixtures\Purger\Driver\PostgreSql;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class PostgreSqlTest extends TestCase
{
    use ProphecyTrait;

    public function testPostgreSql(): void
    {
        $tables = ['table1'];
        $pdo = $this->prophesize(PDO::class);
        $pdo->exec('SET session_replication_role = replica')
            ->shouldBeCalledOnce()
            ->willReturn(1);
        $pdo->exec('SET session_replication_role = origin')
            ->shouldBeCalledOnce()
            ->willReturn(1);
        $pdo->exec('TRUNCATE TABLE "table1" RESTART IDENTITY CASCADE')
            ->shouldBeCalledOnce()
            ->willReturn(1);

        $queryStatement = $this->prophesize(PDOStatement::class);
        $queryStatement->fetchAll(PDO::FETCH_COLUMN)->shouldBeCalledOnce()->willReturn($tables);
        $pdo->query('SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = \'foo\';')
            ->shouldBeCalledOnce()
            ->willReturn($queryStatement->reveal());

        $driver = new PostgreSql(new Configuration(pdo: $pdo->reveal(), databaseName: 'foo'));

        $driver->disableForeignKeys();
        $this->assertArraysAreEqual($tables, $driver->listTables());
        $driver->truncateTable('table1');
        $driver->enableForeignKeys();
    }

    public function testListTablesWithException(): void
    {
        $this->expectException(DatabaseQueryException::class);
        $this->expectExceptionMessage('PostgreSQL database query failed');

        $pdo = $this->prophesize(PDO::class);
        $pdo->query('SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = \'foo\';')
            ->shouldBeCalledOnce()
            ->willReturn(false);

        $driver = new PostgreSql(new Configuration(pdo: $pdo->reveal(), databaseName: 'foo'));
        $driver->listTables();
    }
}
