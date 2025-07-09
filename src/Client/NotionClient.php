<?php

namespace App\Client;

class NotionClient
{
    private string $notionToken;
    private string $notionApiVersion;
    private string $notionBaseUrl;

    public function __construct(string $notionToken, string $notionApiVersion, string $notionBaseUrl)
    {
        $this->notionToken = $notionToken;
        $this->notionApiVersion = $notionApiVersion;
        $this->notionBaseUrl = rtrim($notionBaseUrl, '/');
    }

    private function request(string $method, string $path, array $body = []): array
    {
        $url = $this->notionBaseUrl . $path;
        $headers = [
            "Authorization: Bearer {$this->notionToken}",
            "Notion-Version: {$this->notionApiVersion}",
            "Content-Type: application/json",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            throw new \RuntimeException("cURL request failed for {$url}");
        }

        $data = json_decode($response, true);
        if ($httpCode >= 400) {
            $message = $data['message'] ?? 'Unknown error';
            throw new \RuntimeException("Notion API error ({$httpCode}): {$message}");
        }

        return $data;
    }

    public function retrieveDatabase(string $databaseId): array
    {
        return $this->request('GET', "/databases/{$databaseId}");
    }

    public function queryDatabase(string $databaseId, array $query = []): array
    {
        return $this->request('POST', "/databases/{$databaseId}/query", $query);
    }

    // Add other endpoints as needed, e.g., pages(), search(), etc.
}