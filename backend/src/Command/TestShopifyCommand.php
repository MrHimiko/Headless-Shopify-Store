<?php

namespace App\Command;

use App\Service\Shopify\ShopifyProductService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-shopify',
    description: 'Test Shopify API connection',
)]
class TestShopifyCommand extends Command
{
    private ShopifyProductService $productService;

    public function __construct(ShopifyProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Testing Shopify Storefront API');

        try {
            $io->section('Fetching products...');
            $result = $this->productService->getProducts(5);

            if (isset($result['data']['products']['edges'])) {
                $products = $result['data']['products']['edges'];
                $io->success('Successfully fetched ' . count($products) . ' products!');

                foreach ($products as $edge) {
                    $product = $edge['node'];
                    $io->writeln(sprintf(
                        '- %s (Handle: %s) - %s %s',
                        $product['title'],
                        $product['handle'],
                        $product['priceRange']['minVariantPrice']['amount'],
                        $product['priceRange']['minVariantPrice']['currencyCode']
                    ));
                }
            } else {
                $io->error('No products found in response');
                $io->writeln(json_encode($result, JSON_PRETTY_PRINT));
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}