<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Exception;

use Dknx01\DataFixtures\Attributes\DataFixture;
use Dknx01\DataFixtures\Fixture\FixtureInterface;
use Exception;

use function sprintf;

class FixtureAlreadyLoadedException extends Exception
{
    public function __construct(DataFixture $dataFixture)
    {
        parent::__construct(sprintf(
            'Fixture "%s" already loaded. Multiple instances are not allowed.',
            $dataFixture->fixtureClass instanceof FixtureInterface ? $dataFixture->fixtureClass::class : $dataFixture->fixtureClass
        )
        );
    }
}
