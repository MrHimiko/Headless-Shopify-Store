<?php

namespace App\Service\Shopify;

use Psr\Cache\CacheItemPoolInterface;

class ShopifyCollectionService
{
    private ShopifyClient $client;
    private CacheItemPoolInterface $cache;

    public function __construct(ShopifyClient $client, CacheItemPoolInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function getCollections(int $first = 50): array
    {
        $cacheKey = "collections_{$first}";
        $cached = $this->cache->getItem($cacheKey);

        if ($cached->isHit()) {
            return $cached->get();
        }

        $query = <<<GQL
        {
            collections(first: $first) {
                edges {
                    node {
                        id
                        title
                        handle
                        description
                        image {
                            url
                            altText
                        }
                    }
                }
            }
        }
        GQL;

        $response = $this->client->query($query);
        
        $cached->set($response);
        $cached->expiresAfter(7200);
        $this->cache->save($cached);

        return $response;
    }

    public function getCollectionByHandle(string $handle, int $productsFirst = 50): ?array
    {
        $cacheKey = "collection_{$handle}_{$productsFirst}";
        $cached = $this->cache->getItem($cacheKey);

        if ($cached->isHit()) {
            return $cached->get();
        }

        $query = <<<GQL
        {
            collectionByHandle(handle: "$handle") {
                id
                title
                handle
                description
                image {
                    url
                    altText
                }
                products(first: $productsFirst) {
                    edges {
                        node {
                            id
                            title
                            handle
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
                        }
                    }
                    pageInfo {
                        hasNextPage
                    }
                }
            }
        }
        GQL;

        $response = $this->client->query($query);
        
        if (isset($response['data']['collectionByHandle']) && $response['data']['collectionByHandle'] !== null) {
            $cached->set($response);
            $cached->expiresAfter(3600);
            $this->cache->save($cached);
            return $response;
        }

        return null;
    }

    public function clearCache(string $handle): void
    {
        $this->cache->deleteItem("collection_{$handle}");
    }
}