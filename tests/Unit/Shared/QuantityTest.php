<?php

declare(strict_types=1);

use App\Shared\Domain\Quantity;


it('accept positives quantities', function (): void {
    expect((new Quantity(1))->value)->toBe(1);
});
it('rejects zero and negative quantities', function (int $value): void {
    expect(fn() => new Quantity($value))->toThrow(InvalidArgumentException::class);
})->with([0, -5]);
