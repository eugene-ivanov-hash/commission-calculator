<?php

declare(strict_types=1);

namespace App\Interface;

interface CurrencyRateProviderInterface
{
    public function getRate(string $currency): float;
}
