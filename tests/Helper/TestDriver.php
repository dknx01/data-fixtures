<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Helper;

use Dknx01\DataFixtures\Purger\Configuration;
use Dknx01\DataFixtures\Purger\Driver\AbstractDriver;

class TestDriver extends AbstractDriver
{
    public function __construct(Configuration $configuration)
    {
        $this->pdo = $configuration->pdo;
        $this->detectSchema($configuration);
    }

    public function enableForeignKeys(): void
    {
        // No-op for testing
    }

    public function truncateTable(string $table, bool $withVacuum = true): void
    {
        // No-op for testing
    }

    public function disableForeignKeys(): void
    {
        // No-op for testing
    }

    public function listTables(): array
    {
        return [];
    }

    public function getSchemaName(): string
    {
        return $this->schemaName;
    }
}
