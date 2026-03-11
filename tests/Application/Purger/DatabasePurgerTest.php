<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Application\Purger;

use Dknx01\DataFixtures\Exception\UnsupportedPDODriverException;
use Dknx01\DataFixtures\Purger\Configuration;
use Dknx01\DataFixtures\Purger\DatabasePurger;
use Faker\Factory;
use Faker\Generator;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

use function sprintf;

class DatabasePurgerTest extends TestCase
{
    use ProphecyTrait;

    private PDO $pdo;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->faker = Factory::create();
    }

    public function testGetPdo(): void
    {
        $purger = new DatabasePurger(new Configuration(pdo: $this->pdo));
        $this->assertEquals($this->pdo, $purger->pdoDriver->getPdo());
    }

    public function testPurgeAllTables(): void
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS `users` (name varchar(255) PRIMARY KEY, email varchar(255))');
        $this->pdo->exec(
            sprintf('INSERT INTO `users` (`name`, `email`) VALUES (\'%s\', \'%s\')', $this->faker->lastName(), $this->faker->safeEmail())
        );

        $statement = $this->pdo->query('SELECT count(*) FROM users');
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $this->assertEquals(1, $statement->fetch(PDO::FETCH_NUM)[0]);
        unset($statement);

        $purger = new DatabasePurger(new Configuration(pdo: $this->pdo));
        $purger->purgeAllTables();

        $statement = $this->pdo->query('SELECT count(*) FROM users');
        $this->assertInstanceOf(PDOStatement::class, $statement);
        $this->assertEquals(0, $statement->fetch(PDO::FETCH_NUM)[0]);
    }

    public function testMySQLPDO(): void
    {
        $pdo = $this->prophesize(PDO::class);
        $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)
            ->shouldBeCalled()->willReturn('mysql');
        new DatabasePurger(new Configuration(pdo: $pdo->reveal(), databaseName: 'foo'));
    }

    public function testPostgreSQLDO(): void
    {
        $pdo = $this->prophesize(PDO::class);
        $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)
            ->shouldBeCalled()->willReturn('pgsql');
        new DatabasePurger(new Configuration(pdo: $pdo->reveal(), databaseName: 'foo'));
    }

    public function testUnsupportedPDO(): void
    {
        $this->expectException(UnsupportedPDODriverException::class);
        $this->expectExceptionMessage('Unsupported driver in dsn string: foo');
        $pdo = $this->prophesize(PDO::class);
        $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)
            ->shouldBeCalled()->willReturn('foo');
        new DatabasePurger(new Configuration(pdo: $pdo->reveal(), databaseName: 'foo'));
    }
}
