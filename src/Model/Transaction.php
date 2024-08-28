<?php

declare(strict_types=1);

namespace App\Model;

use function strtoupper;

final readonly class Transaction
{
    private string $currency;

    public function __construct(
        private float $amount,
        private string $bin,
        string $currency,
    ) {
        $this->currency = strtoupper($currency);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
