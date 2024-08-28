<?php

declare(strict_types=1);

namespace App\Service;

use App\Constant\Currency;
use App\Interface\CountryProviderInterface;
use App\Interface\CurrencyRateProviderInterface;
use App\Model\Commission;
use App\Model\Country as CountryModel;
use App\Model\Transaction;

final class CommissionService
{
    private const EU_COMMISSION_RATE = 0.01;
    private const NON_EU_COMMISSION_RATE = 0.02;

    public function __construct(
        private readonly CountryProviderInterface $countryService,
        private readonly CurrencyRateProviderInterface $rateService,
    ) {
    }

    public function calculate(Transaction $transaction): Commission
    {
        $amount = $this->getConvertedAmount($transaction);
        $country = $this->countryService->getCountry($transaction->getBin());
        $commissionRate = $this->getCommissionRate($country);

        return new Commission(
            $amount * $commissionRate
        );
    }

    public function getCommissionRate(CountryModel $country): float
    {
        return $country->isEu() ? self::EU_COMMISSION_RATE : self::NON_EU_COMMISSION_RATE;
    }

    private function getConvertedAmount(Transaction $transaction): float
    {
        if ($transaction->getCurrency() === Currency::EUR) {
            return $transaction->getAmount();
        }
        $rate = $this->rateService->getRate($transaction->getCurrency());

        if ($rate === 0.0) {
            return 0;
        }

        return $transaction->getAmount() / $rate;
    }
}
