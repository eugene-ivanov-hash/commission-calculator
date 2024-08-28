<?php

declare(strict_types=1);

namespace App\Model;

final readonly class Commission
{
    public function __construct(
        private float $amount,
    ) {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
