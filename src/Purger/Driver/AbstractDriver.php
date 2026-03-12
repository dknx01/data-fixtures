<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Purger\Driver;

use Dknx01\DataFixtures\Purger\Configuration;
use InvalidArgumentException;
use PDO;

abstract class AbstractDriver implements PdoDriver
{
    protected PDO $pdo;
    protected ?string $schemaName;

    public function additionalTasks(): void
    {
    }

    public function getDriver(): string
    {
        return $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    protected function detectSchema(Configuration $configuration): void
    {
        if (null !== $configuration->databaseName) {
            $this->schemaName = $configuration->databaseName;

            return;
        }
        if (preg_match('/dbname=([^;]+)/', (string) $configuration->dsn, $m)) {
            $this->schemaName = $m[1];
        } else {
            throw new InvalidArgumentException('Database name could not be extracted from DSN or from configuration.');
        }
    }
}
