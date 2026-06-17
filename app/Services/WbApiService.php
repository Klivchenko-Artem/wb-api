<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WbApiService
{
    private string $host;
    private string $key;

    public function __construct()
    {
        $this->host = config('services.wb_api.host');
        $this->key = config('services.wb_api.key');
    }

    public function fetchAll(string $endpoint, array $params = [], int $startPage = 1): \Generator
    {
        $page = $startPage;
        $limit = 500;

        do {
            $query = array_merge($params, [
                'key' => $this->key,
                'page' => $page,
                'limit' => $limit,
            ]);

            $response = Http::timeout(120)
                ->retry(5, 5000)
                ->get("{$this->host}/api/{$endpoint}", $query);

            if (!$response->ok()) {
                throw new \RuntimeException("API error on {$endpoint} page {$page}: {$response->status()}");
            }

            $json = $response->json();
            $data = $json['data'] ?? [];

            if (empty($data)) {
                break;
            }

            yield ['data' => $data, 'page' => $page, 'lastPage' => $json['meta']['last_page'] ?? $page];

            $lastPage = $json['meta']['last_page'] ?? $page;
            $page++;

            usleep(300000);
        } while ($page <= $lastPage);
    }
}
