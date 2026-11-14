<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Shared\Domain\Money;
use App\Shared\Domain\Currency;
use ValueError;
use InvalidArgumentException;

it('add money of the same currency', function () :void {
    $total = Money::fromAmount(100)->add(Money::fromAmount(200));
    expect($total)->toBeInstanceOf(Money::class);
    expect($total->amount)->toBe(300);
    expect($total->currency)->toBe(Currency::USD);
});

it('only accepts money of the same currency', function () :void {
   expect(Currency::tryFrom('XYZ'))->toBeNull()->and(fn () => Currency::from('XYZ'))->toThrow(ValueError::class);
});

it('rejects negative amounts', function (): void {
    expect(fn() => Money::fromAmount(-1))->toThrow(InvalidArgumentException::class);
});

it('cannot subtract below zero', function (): void {
    expect(fn() => Money::fromAmount(100)->subtract(Money::fromAmount(101)))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects mixing currencies', function (): void {
    expect(fn() => Money::fromAmount(100, Currency::USD)->add(Money::fromAmount(100, Currency::ARS)))
        ->toThrow(InvalidArgumentException::class, 'Currency mismatch');
});

it('formats with the decimals of each currency', function (): void {
    expect(Money::fromAmount(190505)->format())->toBe('USD 1905.05')
        ->and(Money::fromAmount(1905, Currency::JPY)->format())->toBe('JPY 1905');
});
