<?php

namespace App;

use Exception;

class Queue
{
    public const MAX_ITEMS = 5;

    protected array $items = [];

    public function __construct() {}

    public function push(mixed $item): void
    {
        if ($this->getCount() === 5) {
            throw new Exception("MAX_ITEMS");
        } 
        array_push($this->items, $item);
    }

    public function pop(): mixed
    {
        return array_shift($this->items);
    }

    public function getCount(): int
    {
        return count($this->items);
    }
}