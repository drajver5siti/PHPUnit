<?php

namespace App;

use Exception;

class ParserResult
{
    public function __construct(private array $values, private array $defaults)
    {
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function get(string $key): mixed
    {
        return $this->values[$key] ?? $this->defaults[$key] ?? throw new Exception("NO_VALUE");
    }

    public function count(): int
    {
        return count($this->values);
    }
}