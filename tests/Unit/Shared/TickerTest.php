<?php

declare(strict_types=1);

use App\Shared\Domain\Ticker;
use App\Shared\Domain\Quantity;

it('normalizes the symbol', function (): void {
    expect((new Ticker(' aapl '))->value)->toBe('AAPL');
});

it('rejects invalid symbols', function (string $symbol): void {
    expect(fn() => new Ticker($symbol))->toThrow(InvalidArgumentException::class);
})->with(['', 'TOOLONG', 'AB1', 'A-B']);
