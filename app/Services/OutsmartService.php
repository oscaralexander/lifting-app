<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OutsmartService
{
    public function __construct(private readonly Client $client) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getRelations(): array
    {
        try {
            $response = $this->client->get('relations/', [
                'query' => array_merge($this->baseQuery(), [
                    'key' => ['active'],
                    'operator' => ['eq'],
                    'value' => ['1'],
                ]),
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data['response'] ?? [];
        } catch (GuzzleException) {
            return [];
        }
    }

    /**
     * Get the projects belonging to a relation, filtered by debtor number.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getProjects(string $debtorNumber): array
    {
        try {
            $response = $this->client->get('projects/', [
                'query' => array_merge($this->baseQuery(), [
                    'key' => ['debtor_number'],
                    'operator' => ['eq'],
                    'value' => [$debtorNumber],
                ]),
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data['response'] ?? [];
        } catch (GuzzleException) {
            return [];
        }
    }

    /**
     * Get the work orders belonging to a relation, filtered by debtor number.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getWorkOrders(string $debtorNumber): array
    {
        try {
            $response = $this->client->get('GetWorkorders/', [
                'query' => array_merge($this->baseQuery(), [
                    // 'status' => '',
                    'update_status' => 'false',
                    'include_private_photos' => 'true',
                    'key' => ['CustomerDebtorNr'],
                    'operator' => ['eq'],
                    'value' => [$debtorNumber],
                ]),
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data['response'] ?? [];
        } catch (GuzzleException) {
            return [];
        }
    }

    /**
     * @return array<string, string>
     */
    private function baseQuery(): array
    {
        return [
            'token' => config('outsmart.token'),
            'software_token' => config('outsmart.software_token'),
        ];
    }
}
