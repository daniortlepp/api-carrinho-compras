<?php

namespace App\Domain\Entities;

class Cart
{
    /** @var Item[] */
    private array $items = [];

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getInitialTotal(): float
    {
        return array_reduce(
            $this->items,
            fn(float $carry, Item $item) => $carry + $item->getTotal(),
            0.0
        );
    }
}