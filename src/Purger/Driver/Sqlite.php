<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Purger\Driver;

use Dknx01\DataFixtures\Exception\DatabaseQueryException;
use Dknx01\DataFixtures\Purger\Configuration;
use PDO;

use function sprintf;

class Sqlite extends AbstractDriver
{
    public function __construct(Configuration $configuration)
    {
        $this->pdo = $configuration->hasPdo() ? $configuration->pdo : new PDO(...$configuration->getParameters());

        $this->schemaName = null;
    }

    public function disableForeignKeys(): void
    {
        $this->pdo->exec('PRAGMA foreign_keys = OFF');
    }

    public function enableForeignKeys(): void
    {
        $this->pdo->exec('PRAGMA foreign_keys = ON');
    }

    /**
     * Returns an array of table names for the current connection.
     *
     * @return string[]
     */
    public function listTables(): array
    {
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'";

        $query = $this->pdo->query($sql);
        if (!$query) {
            throw new DatabaseQueryException('SQLite database query failed');
        }

        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * SQLite has no TRUNCATE; DELETE + VACUUM is the closest equivalent.
     *
     * @param string $table Table name (already escaped for the driver)
     */
    public function truncateTable(string $table, bool $withVacuum = true): void
    {
        $sql = sprintf('DELETE FROM "%s"', $table);
        $this->pdo->exec($sql);
    }

    public function additionalTasks(): void
    {
        $this->pdo->exec('VACUUM');
    }
}
