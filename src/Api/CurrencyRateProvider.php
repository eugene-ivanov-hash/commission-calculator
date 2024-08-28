<?php

declare(strict_types=1);

namespace App\Api;

use App\Exception\RateNotFoundException;
use App\Interface\CurrencyRateProviderInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyRateProvider implements CurrencyRateProviderInterface
{
    private const API_URL = 'https://exchangeapi.io/latest';

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    /**
     * @throws RateNotFoundException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getRate(string $currency): float
    {
        $response = $this->client->request('GET', self::API_URL);
        $data = $response->toArray();

        if (!isset($data['rates'][$currency])) {
            throw new RateNotFoundException($currency);
        }

        return (float) $data['rates'][$currency];
    }
}
