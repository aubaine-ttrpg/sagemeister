<?php

namespace App\Service;

use App\Client\NotionClient;

class NotionService
{
    private NotionClient $client;

    public function __construct(NotionClient $client)
    {
        $this->client = $client;
    }

    public function getDatabase(string $id): array
    {
        return $this->client->retrieveDatabase($id);
    }

    public function listDatabaseItems(string $id, array $filters = []): array
    {
        return $this->client->queryDatabase($id, $filters);
    }
}