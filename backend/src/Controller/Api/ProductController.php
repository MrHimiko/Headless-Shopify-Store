<?php

namespace App\Controller\Api;

use App\Service\Shopify\ShopifyProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products', name: 'api_products_')]
class ProductController extends AbstractController
{
    private ShopifyProductService $productService;

    public function __construct(ShopifyProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        try {
            $first = $request->query->getInt('first', 50);
            $after = $request->query->get('after');

            $result = $this->productService->getProducts($first, $after);

            return $this->json([
                'success' => true,
                'data' => $result['data']['products']
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/{handle}', name: 'show', methods: ['GET'])]
    public function show(string $handle): JsonResponse
    {
        try {
            $result = $this->productService->getProductByHandle($handle);

            if (!$result || !isset($result['data']['productByHandle'])) {
                return $this->json([
                    'success' => false,
                    'error' => 'Product not found'
                ], 404);
            }

            return $this->json([
                'success' => true,
                'data' => $result['data']['productByHandle']
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}