<?php

namespace App\Controller\Api;

use App\Service\Shopify\ShopifyCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/cart', name: 'api_cart_')]
class CartController extends AbstractController
{
    private ShopifyCartService $cartService;

    public function __construct(ShopifyCartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['lines']) || empty($data['lines'])) {
                return $this->json([
                    'success' => false,
                    'error' => 'Cart lines are required'
                ], 400);
            }

            $cart = $this->cartService->createCart($data['lines']);

            return $this->json([
                'success' => true,
                'checkoutUrl' => $cart['checkoutUrl'],
                'cartId' => $cart['id'],
                'totalQuantity' => $cart['totalQuantity'],
                'cost' => $cart['cost']
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}