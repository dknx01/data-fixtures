<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Purger\Driver;

use Dknx01\DataFixtures\Exception\DatabaseQueryException;
use Dknx01\DataFixtures\Purger\Configuration;
use PDO;

use function sprintf;

class PostgreSql extends AbstractDriver
{
    public function __construct(Configuration $configuration)
    {
        $this->pdo = $configuration->hasPdo() ? $configuration->pdo : new PDO(...$configuration->getParameters());
        $this->detectSchema($configuration);
    }

    /**
     * In PostgreSQL we set the replication role to "replica".
     */
    public function disableForeignKeys(): void
    {
        $this->pdo->exec('SET session_replication_role = replica');
    }

    public function enableForeignKeys(): void
    {
        $this->pdo->exec('SET session_replication_role = origin');
    }

    /**
     * Returns an array of table names for the current connection.
     *
     * @return string[]
     */
    public function listTables(): array
    {
        $sql = sprintf('SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = \'%s\';', $this->schemaName);
        $query = $this->pdo->query($sql);
        if (!$query) {
            throw new DatabaseQueryException('PostgreSQL database query failed');
        }

        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Truncate a table if the dialect supports it; otherwise fall back to DELETE.
     *
     * @param string $table Table name (already escaped for the driver)
     */
    public function truncateTable(string $table, bool $withVacuum = true): void
    {
        // PostgreSQL supports TRUNCATE, optionally RESTART IDENTITY
        $sql = sprintf('TRUNCATE TABLE "%s" RESTART IDENTITY CASCADE', $table);
        $this->pdo->exec($sql);
    }
}
