<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Api\CountryProvider;
use App\Api\CurrencyRateProvider;
use App\Model\Country;
use App\Model\Transaction;
use App\Service\CommissionService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CommissionServiceTest extends TestCase
{
    public static function commissionDataProvider(): array
    {
        return [
            'EU country'     => [1.0, 100, 'DE'],
            'Non EU country' => [2.0, 100, 'US'],
        ];
    }

    #[DataProvider('commissionDataProvider')]
    public function testCalculateCommission($expectation, $amount, $country): void
    {
        $countryProvider = $this->createMock(CountryProvider::class);
        $countryProvider
            ->method('getCountry')
            ->willReturn(new Country($country));

        $rateProvider = $this->createMock(CurrencyRateProvider::class);
        $rateProvider
            ->method('getRate')
            ->willReturn(1.0);

        $commissionService = new CommissionService($countryProvider, $rateProvider);
        $currency = 'EUR';

        $commission = $commissionService->calculate(
            new Transaction($amount, $currency, $country)
        );

        $this->assertEquals($expectation, $commission->getAmount());
    }
}
