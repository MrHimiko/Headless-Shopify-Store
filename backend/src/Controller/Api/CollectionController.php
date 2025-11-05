<?php

namespace App\Controller\Api;

use App\Service\Shopify\ShopifyCollectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/collections', name: 'api_collections_')]
class CollectionController extends AbstractController
{
    private ShopifyCollectionService $collectionService;

    public function __construct(ShopifyCollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        try {
            $first = $request->query->getInt('first', 50);
            
            $result = $this->collectionService->getCollections($first);

            return $this->json([
                'success' => true,
                'data' => $result['data']['collections']
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/{handle}', name: 'show', methods: ['GET'])]
    public function show(string $handle, Request $request): JsonResponse
    {
        try {
            $productsFirst = $request->query->getInt('products_first', 50);
            
            $result = $this->collectionService->getCollectionByHandle($handle, $productsFirst);

            if (!$result || !isset($result['data']['collectionByHandle'])) {
                return $this->json([
                    'success' => false,
                    'error' => 'Collection not found'
                ], 404);
            }

            return $this->json([
                'success' => true,
                'data' => $result['data']['collectionByHandle']
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}