<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Fixture;

use PDO;

interface FixtureInterface
{
    public function load(PDO $pdo): void;
}
