<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Application\Purger;

use Dknx01\DataFixtures\Exception\ConfigurationInvalidException;
use Dknx01\DataFixtures\Purger\Configuration;
use PDO;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ConfigurationTest extends TestCase
{
    use ProphecyTrait;

    public function testHasPdo(): void
    {
        $this->assertTrue(new Configuration(pdo: $this->prophesize(PDO::class)->reveal())->hasPdo());
    }

    public function testGetParameters(): void
    {
        $this->assertArraysAreEqual(
            [
                'dsn' => 'sqlite::memory:',
                'username' => 'foo',
                'password' => 'bar',
                'options' => [
                    'bla' => 1,
                ],
            ],
            new Configuration(dsn: 'sqlite::memory:', user: 'foo', password: 'bar', options: ['bla' => 1])->getParameters(),
        );
    }

    public function testConfigurationWithInvalidData(): void
    {
        $this->expectException(ConfigurationInvalidException::class);
        new Configuration(user: 'foo', password: 'bar');
    }
}
