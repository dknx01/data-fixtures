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

class MySQL extends AbstractDriver
{
    public function __construct(Configuration $configuration)
    {
        $this->pdo = $configuration->hasPdo() ? $configuration->pdo : new PDO(...$configuration->getParameters());

        $this->detectSchema($configuration);
    }

    public function disableForeignKeys(): void
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    }

    public function enableForeignKeys(): void
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Returns an array of table names for the current connection.
     *
     * @return string[]
     *
     * @throws DatabaseQueryException
     */
    public function listTables(): array
    {
        $sql = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = :schema';
        $stmt = $this->pdo->prepare($sql);
        $query = $stmt->execute([':schema' => $this->schemaName]);
        if (!$query) {
            throw new DatabaseQueryException('MySQL database query failed');
        }

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Truncate a table if the dialect supports it; otherwise fall back to DELETE.
     *
     * @param string $table Table name (already escaped for the driver)
     */
    public function truncateTable(string $table, bool $withVacuum = true): void
    {
        $sql = sprintf('TRUNCATE TABLE `%s`', $table);
        $this->pdo->exec($sql);
    }
}
