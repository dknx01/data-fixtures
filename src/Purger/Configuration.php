<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Purger;

use Dknx01\DataFixtures\Exception\ConfigurationInvalidException;
use PDO;

final class Configuration
{
    /**
     * @param array<array-key, int|string|bool> $options
     *
     * @throws ConfigurationInvalidException
     */
    public function __construct(
        public readonly ?PDO $pdo = null,
        public readonly ?string $databaseName = null,
        public readonly ?string $dsn = null,
        public readonly ?string $user = null,
        public readonly ?string $password = null,
        public array $options = [],
    ) {
        if (null === $dsn && null === $pdo) {
            throw new ConfigurationInvalidException();
        }
    }

    public function hasPdo(): bool
    {
        return isset($this->pdo);
    }

    /**
     * @return array{
     *     dsn: string|null,
     *     username: string|null,
     *     password: string|null,
     *     options: array<array-key, int|string|bool>
     * }
     */
    public function getParameters(): array
    {
        return [
            'dsn' => $this->dsn,
            'username' => $this->user,
            'password' => $this->password,
            'options' => $this->options,
        ];
    }
}
