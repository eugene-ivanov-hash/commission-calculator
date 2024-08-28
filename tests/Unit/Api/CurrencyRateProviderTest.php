<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api;

use App\Api\CurrencyRateProvider;
use App\Tests\Trait\MockCurrencyRateProviderTrait;
use PHPUnit\Framework\TestCase;

class CurrencyRateProviderTest extends TestCase
{
    use MockCurrencyRateProviderTrait;

    public function testGetRate(): void
    {
        $client = $this->mockCurrencyRateProviderClient();
        $currencyRateProvider = new CurrencyRateProvider($client);
        $this->assertEquals(0.9, $currencyRateProvider->getRate('USD'));
    }
}
