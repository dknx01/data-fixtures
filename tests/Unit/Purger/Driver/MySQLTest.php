<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Unit\Purger\Driver;

use Dknx01\DataFixtures\Exception\DatabaseQueryException;
use Dknx01\DataFixtures\Purger\Configuration;
use Dknx01\DataFixtures\Purger\Driver\MySQL;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class MySQLTest extends TestCase
{
    use ProphecyTrait;

    public function testMySql(): void
    {
        $tables = ['table1'];
        $pdo = $this->prophesize(PDO::class);
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0')
            ->shouldBeCalledOnce()
            ->willReturn(1);
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1')
            ->shouldBeCalledOnce()
            ->willReturn(1);
        $pdo->exec('TRUNCATE TABLE `table1`')
            ->shouldBeCalledOnce()
            ->willReturn(1);

        $queryStatement = $this->prophesize(PDOStatement::class);
        $queryStatement->execute([':schema' => 'foo'])->shouldBeCalledOnce()->willReturn(true);
        $queryStatement->fetchAll(PDO::FETCH_COLUMN)->shouldBeCalledOnce()->willReturn($tables);
        $pdo->prepare('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = :schema')
            ->shouldBeCalledOnce()
            ->willReturn($queryStatement->reveal());

        $driver = new MySQL(new Configuration(pdo: $pdo->reveal(), databaseName: 'foo'));

        $driver->disableForeignKeys();
        $this->assertArraysAreEqual($tables, $driver->listTables());
        $driver->truncateTable('table1');
        $driver->additionalTasks();
        $driver->enableForeignKeys();
    }

    public function testListTablesWithException(): void
    {
        $this->expectException(DatabaseQueryException::class);
        $this->expectExceptionMessage('MySQL database query failed');

        $pdo = $this->prophesize(PDO::class);
        $queryStatement = $this->prophesize(PDOStatement::class);
        $queryStatement->execute([':schema' => 'foo'])->shouldBeCalledOnce()->willReturn(false);
        $pdo->prepare('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = :schema')
            ->shouldBeCalledOnce()
            ->willReturn($queryStatement->reveal());

        $driver = new MySQL(new Configuration(pdo: $pdo->reveal(), databaseName: 'foo'));
        $driver->listTables();
    }
}
