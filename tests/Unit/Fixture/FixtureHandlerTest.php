<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Unit\Fixture;

use Dknx01\DataFixtures\Attributes\DataFixture;
use Dknx01\DataFixtures\Exception\FixtureAlreadyLoadedException;
use Dknx01\DataFixtures\Fixture\DataFixtureTrait;
use Dknx01\DataFixtures\Fixture\FixtureCollection;
use Dknx01\DataFixtures\Fixture\FixtureHandler;
use Dknx01\DataFixtures\Purger\Configuration;
use Dknx01\DataFixtures\Tests\Helper\SimpleFixture;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class FixtureHandlerTest extends TestCase
{
    use DataFixtureTrait;
    use ProphecyTrait;

    public function testHandle(): void
    {
        $pdo = $this->prophesize(PDO::class);
        $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)->shouldBeCalledOnce()->willReturn('sqlite');
        $pdo->exec('PRAGMA foreign_keys = OFF')->shouldBeCalledOnce()->willReturn(1);
        $pdo->exec('PRAGMA foreign_keys = ON')->shouldBeCalledOnce()->willReturn(1);
        $pdo->exec('VACUUM')->shouldBeCalledOnce()->willReturn(1);

        $queryStatement = $this->prophesize(PDOStatement::class);
        $tables = [];
        $queryStatement->fetchAll(PDO::FETCH_COLUMN)->shouldBeCalledOnce()->willReturn($tables);
        $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")
            ->shouldBeCalledOnce()
            ->willReturn($queryStatement->reveal());

        $configuration = new Configuration(pdo: $pdo->reveal(), databaseName: 'foo');

        $this->executeFixtures($configuration);
    }

    #[DataFixture(SimpleFixture::class)]
    #[DataFixture(SimpleFixture::class)]
    public function testHandleWithMultipleFixtures(): void
    {
        $this->expectException(FixtureAlreadyLoadedException::class);
        $this->loadFixtures();
    }

    public function testHandleWithFixtures(): void
    {
        $pdo = $this->prophesize(PDO::class);
        $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)->shouldBeCalledOnce()->willReturn('sqlite');
        $pdo->exec('PRAGMA foreign_keys = OFF')->shouldBeCalledOnce()->willReturn(1);
        $pdo->exec('PRAGMA foreign_keys = ON')->shouldBeCalledOnce()->willReturn(1);
        $pdo->exec('VACUUM')->shouldBeCalledOnce()->willReturn(1);

        $queryStatement = $this->prophesize(PDOStatement::class);
        $tables = [];
        $queryStatement->fetchAll(PDO::FETCH_COLUMN)->shouldBeCalledOnce()->willReturn($tables);
        $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")
            ->shouldBeCalledOnce()
            ->willReturn($queryStatement->reveal());

        $configuration = new Configuration(pdo: $pdo->reveal(), databaseName: 'foo');

        $fixtures = new FixtureCollection();
        $fixtures->add(new SimpleFixture());
        $handler = new FixtureHandler($configuration);
        $handler->handle($fixtures);
    }
}
