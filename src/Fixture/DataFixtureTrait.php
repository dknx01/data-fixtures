<?php

declare(strict_types=1);

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Fixture;

use Dknx01\DataFixtures\Attributes\DataFixture;
use Dknx01\DataFixtures\Attributes\DependFixture;
use Dknx01\DataFixtures\Attributes\OrderedFixture;
use Dknx01\DataFixtures\Contract\FakerAware;
use Dknx01\DataFixtures\Exception\FixtureAlreadyLoadedException;
use Dknx01\DataFixtures\Faker\FakerTrait;
use Dknx01\DataFixtures\Purger\Configuration;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;

use function count;

use const DEBUG_BACKTRACE_IGNORE_ARGS;

trait DataFixtureTrait
{
    use FakerTrait;
    /** @var FixtureCollection<array-key, FixtureInterface>|null */
    private ?FixtureCollection $fixtures = null;

    /**
     * @throws Throwable
     */
    protected function executeFixtures(Configuration $configuration): void
    {
        new FixtureHandler($configuration)->handle($this->fixtures ?: new FixtureCollection());
    }

    /**
     * @throws ReflectionException
     * @throws FixtureAlreadyLoadedException
     */
    protected function loadFixtures(int $stackPosition = 1): void
    {
        if (!$this->fixtures instanceof FixtureCollection) {
            $this->fixtures = new FixtureCollection();
        }

        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        $reflection = new ReflectionClass($stack[$stackPosition]['class']);
        foreach ($reflection->getAttributes(DataFixture::class) as $attribute) {
            /** @var DataFixture $attrInstance */
            $attrInstance = $attribute->newInstance();
            $this->addFixture($attrInstance);
        }

        $reflection = new ReflectionMethod($stack[$stackPosition]['class'], $stack[$stackPosition]['function']);
        foreach ($reflection->getAttributes(DataFixture::class) as $attribute) {
            /** @var DataFixture $attrInstance */
            $attrInstance = $attribute->newInstance();
            $this->addFixture($attrInstance);
        }
        $this->processDependentFixtures();
    }

    /**
     * @throws FixtureAlreadyLoadedException
     */
    private function addFixture(DataFixture $dataFixture): void
    {
        if ($this->fixtureAlreadyLoaded($dataFixture)) {
            throw new FixtureAlreadyLoadedException($dataFixture);
        }
        $fixture = $this->getFixture($dataFixture);
        $reflClass = new ReflectionClass($fixture);
        $reflAttributes = $reflClass->getAttributes(OrderedFixture::class);
        if (0 === count($reflAttributes)) {
            $this->fixtures->add($fixture);
        } else {
            /** @var OrderedFixture $orderedData */
            $orderedData = $reflAttributes[0]->newInstance();
            $this->fixtures->addAt($orderedData->position, $fixture);
        }
    }

    private function fixtureAlreadyLoaded(DependFixture|DataFixture $attrInstance): bool
    {
        return $this->fixtures->has($attrInstance->fixtureClass);
    }

    private function getFixture(DataFixture $attrInstance): FixtureInterface
    {
        $fixture = $attrInstance->fixtureClass instanceof FixtureInterface
            ? $attrInstance->fixtureClass
            : new $attrInstance->fixtureClass();
        if ($fixture instanceof FakerAware) {
            $fixture->setFaker(self::getFaker());
        }

        return $fixture;
    }

    /**
     * @throws ReflectionException
     */
    private function processDependentFixtures(): void
    {
        foreach ($this->fixtures as $fixture) {
            $reflection = new ReflectionClass($fixture);
            foreach ($reflection->getAttributes(DependFixture::class) as $attribute) {
                /** @var DependFixture $attrInstance */
                $attrInstance = $attribute->newInstance();
                $dependFixture = new DataFixture($attrInstance->fixtureClass);
                try {
                    $this->addFixture($dependFixture);
                } catch (FixtureAlreadyLoadedException $e) {
                    // Do nothing as the dependent fixture was already loaded, which may occur.
                }
            }
        }
    }
}
