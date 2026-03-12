<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Faker;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait
{
    /** @var string[] */
    protected static array $fakerProviders = [];
    protected static string $fakerLocale = 'en_EN';
    protected static Generator $faker;

    protected static function loadFaker(): void
    {
        self::$faker = Factory::create(self::$fakerLocale);
        foreach (self::$fakerProviders as $fakerProvider) {
            self::$faker->addProvider(new $fakerProvider(self::$faker));
        }
    }

    protected static function createFaker(): Generator
    {
        $faker = Factory::create(self::$fakerLocale);
        foreach (self::$fakerProviders as $fakerProvider) {
            $faker->addProvider(new $fakerProvider($faker));
        }

        return $faker;
    }

    protected static function getFaker(): Generator
    {
        if (!isset(self::$faker)) {
            self::loadFaker();
        }

        return self::$faker;
    }

    /**
     * @param array{
     *      'locale': string,
     *      'providers': array<array-key, string>
     * } $configuration
     */
    protected static function prepareFaker(array $configuration): void
    {
        static::$fakerLocale = $configuration['locale'];
        static::$fakerProviders = $configuration['providers'];
    }
}
