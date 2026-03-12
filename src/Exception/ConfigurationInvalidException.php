<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Exception;

use Exception;

class ConfigurationInvalidException extends Exception
{
    public function __construct()
    {
        parent::__construct('You must provide a dsn or an existing PDO connection');
    }
}
