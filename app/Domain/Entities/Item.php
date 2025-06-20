<?php

namespace App\Domain\Entities;

class Item
{
    public function __construct(
        public readonly string $name,
        public readonly float $unitPrice,
        public readonly int $quantity
    ) {}

    public function getTotal(): float
    {
        return $this->unitPrice * $this->quantity;
    }
}