<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api;

use App\Api\CountryProvider;
use App\Tests\Trait\MockCountryProviderTrait;
use PHPUnit\Framework\TestCase;

class CountryProviderTest extends TestCase
{
    use MockCountryProviderTrait;

    public function testGetCountryByCode(): void
    {
        $client = $this->mockCountryProviderClient();

        $countryProvider = new CountryProvider($client);
        $country = $countryProvider->getCountry('some_bin');

        $this->assertEquals('DE', $country->getCode());
        $this->assertTrue($country->isEu());
    }
}
