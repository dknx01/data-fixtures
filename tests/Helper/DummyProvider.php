<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Tests\Helper;

use Faker\Provider\Base;

class DummyProvider extends Base
{
    public function dummy(): string
    {
        return 'dummy';
    }
}
