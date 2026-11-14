<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use InvalidArgumentException;
final readonly class UserId{

public function __construct(public string $value)
{
    if (trim($value) ===''){
        throw new InvalidArgumentException('UserId cannot empty');
    }
}



}
