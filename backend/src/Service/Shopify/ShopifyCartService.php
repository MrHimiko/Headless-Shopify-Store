<?php

namespace App\Service\Shopify;

class ShopifyCartService
{
    private ShopifyClient $client;

    public function __construct(ShopifyClient $client)
    {
        $this->client = $client;
    }

    public function createCart(array $lines): array
    {
        $linesInput = [];
        foreach ($lines as $line) {
            $linesInput[] = sprintf(
                '{merchandiseId: "%s", quantity: %d}',
                $line['merchandiseId'],
                $line['quantity']
            );
        }
        
        $linesString = implode(', ', $linesInput);
        
        $mutation = <<<GQL
        mutation {
            cartCreate(
                input: {
                    lines: [{$linesString}]
                }
            ) {
                cart {
                    id
                    checkoutUrl
                    totalQuantity
                    cost {
                        subtotalAmount {
                            amount
                            currencyCode
                        }
                        totalAmount {
                            amount
                            currencyCode
                        }
                    }
                }
                userErrors {
                    field
                    message
                }
            }
        }
        GQL;

        $response = $this->client->query($mutation);

        if (isset($response['data']['cartCreate']['userErrors']) && 
            !empty($response['data']['cartCreate']['userErrors'])) {
            throw new \Exception('Cart creation failed: ' . 
                json_encode($response['data']['cartCreate']['userErrors']));
        }

        return $response['data']['cartCreate']['cart'];
    }
}