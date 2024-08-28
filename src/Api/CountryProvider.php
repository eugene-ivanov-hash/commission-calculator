<?php

declare(strict_types=1);

namespace App\Api;

use App\Exception\CountryNotFoundException;
use App\Exception\RateLimitException;
use App\Interface\CountryProviderInterface;
use App\Model\Country;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function sprintf;

class CountryProvider implements CountryProviderInterface
{
    private const BASE_URL = 'https://lookup.binlist.net/';

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    /**
     * @throws ClientExceptionInterface
     * @throws CountryNotFoundException
     * @throws DecodingExceptionInterface
     * @throws RateLimitException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCountry(string $bin): Country
    {
        $response = $this->client->request('GET', sprintf('%s%s', self::BASE_URL, $bin));

        if (429 === $response->getStatusCode()) {
            throw new RateLimitException(self::BASE_URL, 'Try again after 1 hour');
        }

        $data = $response->toArray();

        if (!isset($data['country']['alpha2'])) {
            throw new CountryNotFoundException($bin, $response->getContent());
        }

        return new Country($data['country']['alpha2']);
    }
}
