<?php

declare(strict_types=1);

namespace App\Tests\Trait;

use App\Api\CountryProvider;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait MockCountryProviderTrait
{
    public function getCountryProvider(): CountryProvider
    {
        $this->mockCountryProviderClient();

        return new CountryProvider($this->mockCountryProviderClient());
    }

    public function mockCountryProviderClient(): HttpClientInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->method('toArray')
            ->willReturn(['country' => ['alpha2' => 'DE']]);

        $client = $this->createMock(HttpClientInterface::class);
        $client
            ->method('request')
            ->willReturn($response);

        return $client;
    }
}
