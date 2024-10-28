<?php
declare(strict_types=1);

namespace App\Integrations;

use GuzzleHttp\Client;

final class MonobankApi
{
    private string $baseUrl = 'https://api.monobank.ua';
    private Client $http;

    public function __construct(
        private readonly string $token
    )
    {
        $this->http = new Client([
            'base_uri' => $this->baseUrl,
        ]);
    }

    public function getClientInfo(): array
    {
        $response = $this->http->get('/personal/client-info', [
            'headers' => [
                'X-Token' => $this->token,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getAccounts(): array
    {
        $info = $this->getClientInfo();

        return $info['accounts'];
    }
}
