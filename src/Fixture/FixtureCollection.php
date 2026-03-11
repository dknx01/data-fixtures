<?php

/*
 * This file is part of the dknx01/data-fixtures package.
 * (c) dknx01/data-fixtures
 */

namespace Dknx01\DataFixtures\Fixture;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OutOfBoundsException;
use ReturnTypeWillChange;
use Traversable;

use function count;

/**
 * @template TKey
 * @template TValue
 *
 * @implements IteratorAggregate<array-key, FixtureInterface>
 */
class FixtureCollection implements IteratorAggregate, Countable
{
    /** @var FixtureInterface[] */
    private array $fixtures = [];

    public function add(FixtureInterface $value): void
    {
        $this->fixtures[] = $value;
    }

    /**
     * Insert a value at a specific zero‑based position.
     *
     * All elements that currently occupy $position or a higher index are
     * shifted one place to the right.
     *
     * @param int              $position Zero‑based index where the value should land
     * @param FixtureInterface $value    Value to insert
     *
     * @throws OutOfBoundsException if $position is negative or beyond the
     *                              allowed range (0 … count())
     */
    public function addAt(int $position, FixtureInterface $value): void
    {
        $size = count($this->fixtures);

        if ($position < 0 || $position > $size) {
            throw new OutOfBoundsException("Position {$position} is out of bounds (allowed 0‑{$size}).");
        }

        array_splice($this->fixtures, $position, 0, [$value]);
    }

    /**
     * Remove the element at the given position.
     *
     * @param int $position Zero‑based index to remove
     *
     * @throws OutOfBoundsException if $position is invalid
     */
    public function removeAt(int $position): void
    {
        $size = count($this->fixtures);
        if ($position < 0 || $position >= $size) {
            throw new OutOfBoundsException("Cannot remove position {$position}; valid range is 0‑".($size - 1));
        }

        array_splice($this->fixtures, $position, 1);
    }

    /**
     * @return FixtureInterface[]
     */
    public function toArray(): array
    {
        return $this->fixtures;
    }

    #[ReturnTypeWillChange]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->fixtures);
    }

    public function count(): int
    {
        return count($this->fixtures);
    }

    public function has(string|FixtureInterface $fixtureClass): bool
    {
        return null !== array_find_key(
            $this->fixtures,
            static fn (object $f) => $f::class === ($fixtureClass instanceof FixtureInterface ? $fixtureClass::class : $fixtureClass)
        );
    }
}
