<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Contract;

use Faker\Generator;

interface FakerAware
{
    public function setFaker(Generator $faker): void;
}
