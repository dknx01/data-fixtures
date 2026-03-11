<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Purger\Driver;

use Dknx01\DataFixtures\Purger\Configuration;
use PDO;

interface PdoDriver
{
    public function __construct(Configuration $configuration);

    public function enableForeignKeys(): void;

    /**Truncate a table if the dialect supports it; otherwise fall back to DELETE.
     *
     * @param string $table Table name (already escaped for the driver)
     */
    public function truncateTable(string $table, bool $withVacuum = true): void;

    public function disableForeignKeys(): void;

    /**
     * Returns an array of table names for the current connection.
     *
     * @return string[]
     */
    public function listTables(): array;

    public function getPdo(): PDO;

    public function getDriver(): string;

    public function additionalTasks(): void;
}
