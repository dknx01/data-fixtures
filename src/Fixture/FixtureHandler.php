<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Fixture;

use Dknx01\DataFixtures\Purger\Configuration;
use Dknx01\DataFixtures\Purger\DatabasePurger;
use Throwable;

readonly class FixtureHandler
{
    public function __construct(private readonly Configuration $configuration)
    {
    }

    /**
     * @param FixtureCollection<array-key, FixtureInterface> $fixtures
     * @param bool                                           $append   purge the tables or append/add the data and keep current ones
     *
     * @throws Throwable
     */
    public function handle(FixtureCollection $fixtures, bool $append = false): void
    {
        $purger = new DatabasePurger($this->configuration);
        if (!$append) {
            $purger->purgeAllTables();
        }
        foreach ($fixtures as $fixture) {
            $fixture->load($this->configuration->hasPdo() ? $this->configuration->pdo : $purger->pdoDriver->getPdo());
        }
    }
}
