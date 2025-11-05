<?php

namespace App\Service\Shopify;

class ShopifyClient
{
    private string $storeDomain;
    private string $apiVersion;
    private string $storefrontPrivateToken;

    public function __construct(
        string $shopifyStoreDomain,
        string $shopifyApiVersion,
        string $shopifyStorefrontPrivateToken
    ) {
        $this->storeDomain = $shopifyStoreDomain;
        $this->apiVersion = $shopifyApiVersion;
        $this->storefrontPrivateToken = $shopifyStorefrontPrivateToken;
    }

    public function query(string $query, array $variables = []): array
    {
        $url = "https://{$this->storeDomain}/api/{$this->apiVersion}/graphql.json";
        
        $payload = ['query' => $query];
        if (!empty($variables)) {
            $payload['variables'] = $variables;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Shopify-Storefront-Private-Token: ' . $this->storefrontPrivateToken
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception("Shopify API error: HTTP {$httpCode} - {$response}");
        }

        $decoded = json_decode($response, true);

        if (isset($decoded['errors'])) {
            throw new \Exception('GraphQL errors: ' . json_encode($decoded['errors']));
        }

        return $decoded;
    }
}