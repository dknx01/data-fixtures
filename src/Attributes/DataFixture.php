<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Attributes;

use Attribute;
use Dknx01\DataFixtures\Fixture\FixtureInterface;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
readonly class DataFixture
{
    public function __construct(
        public string|FixtureInterface $fixtureClass,
    ) {
    }
}
