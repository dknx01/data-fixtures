<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Attributes;

use Attribute;
use Dknx01\DataFixtures\Contract\FixtureInterface;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
readonly class DependFixture
{
    public function __construct(
        public string|FixtureInterface $fixtureClass,
    ) {
    }
}
