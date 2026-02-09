<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WooCommerceController extends AbstractController
{
    #[Route('/shop', name: 'app_shop')]
    public function products(): Response
    {
        $products = [
            [
                'id' => 1,
                'title' => 'Product 1',
                'slug' => 'product-1',
                'price' => 29.99,
                'description' => 'Product description',
            ],
            [
                'id' => 2,
                'title' => 'Product 2',
                'slug' => 'product-2',
                'price' => 39.99,
                'description' => 'Product description',
            ],
        ];

        return $this->render('woocommerce/archive-product.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{slug}', name: 'app_product_show')]
    public function product(string $slug): Response
    {
        $product = [
            'id' => 1,
            'title' => ucfirst(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'price' => 29.99,
            'description' => 'Detailed product description',
            'rating' => 4.5,
        ];

        return $this->render('woocommerce/single-product.html.twig', [
            'product' => $product,
        ]);
    }
}
