<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Exception;

use Dknx01\DataFixtures\Purger\Configuration;
use Exception;
use PDO;

class UnsupportedPDODriverException extends Exception
{
    public function __construct(Configuration $configuration)
    {
        parent::__construct(
            'Unsupported driver in dsn string: '.
            ($configuration->hasPdo() ? $configuration->pdo?->getAttribute(PDO::ATTR_DRIVER_NAME) : $configuration->dsn),
        );
    }
}
