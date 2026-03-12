<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class OrderedFixture
{
    public function __construct(
        public int $position,
    ) {
    }
}
