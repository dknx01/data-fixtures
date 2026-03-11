<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Purger;

use Dknx01\DataFixtures\Exception\UnsupportedPDODriverException;
use Dknx01\DataFixtures\Purger\Driver\MySQL;
use Dknx01\DataFixtures\Purger\Driver\PdoDriver;
use Dknx01\DataFixtures\Purger\Driver\PostgreSql;
use Dknx01\DataFixtures\Purger\Driver\Sqlite;
use PDO;
use PDOException;
use Throwable;

class DatabasePurger
{
    public PdoDriver $pdoDriver {
        get {
            return $this->pdoDriver;
        }
    }

    public function __construct(Configuration $configuration)
    {
        $configuration->options += [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_AUTOCOMMIT => false,
        ];

        $this->pdoDriver = match (true) {
            $this->isSqlite($configuration) => new Sqlite($configuration),
            $this->isMySQL($configuration) => new MySQL($configuration),
            $this->isPostgreSql($configuration) => new PostgreSql($configuration),
            default => throw new UnsupportedPDODriverException($configuration),
        };
    }

    /**
     * Purge (truncate) every table in the connected database.
     *
     * @throws PDOException|Throwable
     */
    public function purgeAllTables(): void
    {
        try {
            $this->pdoDriver->disableForeignKeys();
            $tables = $this->pdoDriver->listTables();
            foreach ($tables as $table) {
                $this->pdoDriver->truncateTable($table);
            }
            $this->pdoDriver->enableForeignKeys();

            $this->pdoDriver->additionalTasks();
        } catch (Throwable $e) {
            // Make sure FK checks are turned back on even if we failed
            $this->pdoDriver->enableForeignKeys();
            throw $e;
        }
    }

    private function isSqlite(Configuration $configuration): bool
    {
        return $configuration->hasPdo()
            ? 'sqlite' === $configuration->pdo?->getAttribute(PDO::ATTR_DRIVER_NAME)
            : str_starts_with((string) $configuration->dsn, 'sqlite:');
    }

    private function isMySQL(Configuration $configuration): bool
    {
        return $configuration->hasPdo()
            ? 'mysql' === $configuration->pdo?->getAttribute(PDO::ATTR_DRIVER_NAME)
            : str_starts_with((string) $configuration->dsn, 'mysql:');
    }

    private function isPostgreSQL(Configuration $configuration): bool
    {
        return $configuration->hasPdo()
            ? 'pgsql' === $configuration->pdo?->getAttribute(PDO::ATTR_DRIVER_NAME)
            : str_starts_with((string) $configuration->dsn, 'pgsql:');
    }
}
