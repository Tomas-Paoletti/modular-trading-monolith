<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use InvalidArgumentException;
use Money\Currencies\ISOCurrencies;
use Money\Currency as MoneyCurrency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money as MoneyPhp;

final readonly class Money
{

    public int $amount;
    public Currency $currency;


    public function __construct(private MoneyPhp $money)
    {
        if ($money->isNegative()) {
            throw new InvalidArgumentException('Money cannot be negative');
        }
        $this->amount = (int)$money->getAmount();
        $this->currency = Currency::from($money->getCurrency()->getCode());
    }

    public static function fromAmount(int $amount, Currency $currency = Currency::USD): self
    {
        return new self(new MoneyPhp($amount, new MoneyCurrency($currency->value)));
    }

    public static function zero(Currency $currency = Currency::USD): self
    {
        return self::fromAmount(0, $currency);
    }

    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->money->add($other->money));
    }

    public function subtract(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->money->subtract($other->money));
    }

    public function multiply(int $multiplier): self
    {
        return new self($this->money->multiply($multiplier));
    }

    public function isGreaterThanOrEqual(Money $other): bool
    {
        $this->assertSameCurrency($other);
        return $this->money->greaterThanOrEqual($other->money);
    }

    public function format(): string
    {
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());
        return $this->currency->value . ' ' . $formatter->format($this->money);
    }

    private function assertSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Currency mismatch');
        }
    }
}
