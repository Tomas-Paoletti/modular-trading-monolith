<?php

declare(strict_types=1);

namespace App\Shared\Domain;
use InvalidArgumentException;

final readonly class Ticker
{
    public string $value;
    public function __construct(string $value)
    {
        $value = strtoupper(trim($value));
        if (!preg_match('/^[A-Z]{1,5}$/', $value)) {
            throw new InvalidArgumentException('Invalid ticker');
        }
        $this->value = $value;
    }

}
