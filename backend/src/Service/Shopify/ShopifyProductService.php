<?php

namespace App\Service\Shopify;

use Psr\Cache\CacheItemPoolInterface;

class ShopifyProductService
{
    private ShopifyClient $client;
    private CacheItemPoolInterface $cache;

    public function __construct(ShopifyClient $client, CacheItemPoolInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function getProducts(int $first = 50, ?string $after = null): array
    {
        $cacheKey = "products_{$first}_" . ($after ?? 'start');
        $cached = $this->cache->getItem($cacheKey);

        if ($cached->isHit()) {
            return $cached->get();
        }

        $afterParam = $after ? ", after: \"$after\"" : '';
        
        $query = <<<GQL
        {
            products(first: $first$afterParam) {
                edges {
                    cursor
                    node {
                        id
                        title
                        handle
                        description
                        priceRange {
                            minVariantPrice {
                                amount
                                currencyCode
                            }
                        }
                        images(first: 1) {
                            edges {
                                node {
                                    url
                                    altText
                                }
                            }
                        }
                        variants(first: 1) {
                            edges {
                                node {
                                    id
                                    availableForSale
                                }
                            }
                        }
                    }
                }
                pageInfo {
                    hasNextPage
                    endCursor
                }
            }
        }
        GQL;

        $response = $this->client->query($query);
        
        $cached->set($response);
        $cached->expiresAfter(3600);
        $this->cache->save($cached);

        return $response;
    }

    public function getProductByHandle(string $handle): ?array
    {
        $cacheKey = "product_{$handle}";
        $cached = $this->cache->getItem($cacheKey);

        if ($cached->isHit()) {
            return $cached->get();
        }

        $query = <<<GQL
        {
            productByHandle(handle: "$handle") {
                id
                title
                handle
                description
                descriptionHtml
                options {
                    name
                    values
                }
                priceRange {
                    minVariantPrice {
                        amount
                        currencyCode
                    }
                }
                images(first: 10) {
                    edges {
                        node {
                            url
                            altText
                            width
                            height
                        }
                    }
                }
                variants(first: 100) {
                    edges {
                        node {
                            id
                            title
                            price {
                                amount
                                currencyCode
                            }
                            compareAtPrice {
                                amount
                                currencyCode
                            }
                            availableForSale
                            quantityAvailable
                            selectedOptions {
                                name
                                value
                            }
                        }
                    }
                }
                seo {
                    title
                    description
                }
            }
        }
        GQL;

        $response = $this->client->query($query);
        
        if (isset($response['data']['productByHandle']) && $response['data']['productByHandle'] !== null) {
            $cached->set($response);
            $cached->expiresAfter(1800);
            $this->cache->save($cached);
            return $response;
        }

        return null;
    }

    public function clearCache(string $handle): void
    {
        $this->cache->deleteItem("product_{$handle}");
    }

    public function clearAllCache(): void
    {
        $this->cache->deleteItems(['products_', 'product_']);
    }
}