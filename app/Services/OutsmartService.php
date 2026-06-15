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
                    // 'include_private_photos' => 'false',
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
     * Get the employees from OutSmart.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getEmployees(): array
    {
        try {
            $response = $this->client->get('employees/', [
                'query' => $this->baseQuery(),
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data['response'] ?? [];
        } catch (GuzzleException) {
            return [];
        }
    }

    /**
     * Get a single employee by their unique number.
     *
     * The Outsmart `employees/` endpoint ignores the key/operator/value filter
     * and always returns the full list, so the match is performed client-side.
     *
     * @return array<string, mixed>|null
     */
    public function getEmployee(string $number): ?array
    {
        foreach ($this->getEmployees() as $employee) {
            if ((string) ($employee['number'] ?? '') === $number) {
                return $employee;
            }
        }

        return null;
    }

    /**
     * Get a single work order by its WBA database row id.
     *
     * @return array<string, mixed>|null
     */
    public function getWorkOrder(string $id): ?array
    {
        try {
            $response = $this->client->get('GetWorkorder/', [
                'query' => array_merge($this->baseQuery(), [
                    'row_id' => $id,
                    'update_status' => 'false',
                    'include_private_photos' => 'true',
                ]),
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $workOrder = $data['response'] ?? null;

            if (is_array($workOrder) && array_is_list($workOrder)) {
                return $workOrder[0] ?? null;
            }

            return $workOrder;
        } catch (GuzzleException) {
            return null;
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
