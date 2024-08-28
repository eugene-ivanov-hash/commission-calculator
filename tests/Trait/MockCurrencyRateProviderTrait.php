<?php

declare(strict_types=1);

namespace App\Tests\Trait;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait MockCurrencyRateProviderTrait
{
    public function mockCurrencyRateProviderClient(): HttpClientInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->method('toArray')
            ->willReturn([
                'rates' => [
                    'USD' => 0.9,
                    'JPY' => 110.0,
                    'EUR' => 1,
                    'GBP' => 0.8,
                ],
            ]);

        $client = $this->createMock(HttpClientInterface::class);
        $client
            ->method('request')
            ->willReturn($response);

        return $client;
    }
}
