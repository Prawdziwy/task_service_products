<?php

namespace App\ResponseBuilder;

use App\Service\Cart\Cart;

class CartBuilder
{
    public function __invoke(Cart $cart): array
    {
        $data = [
            'total_price' => $cart->getTotalPrice(),
            'products' => []
        ];

        foreach ($cart->getProducts() as $cartProduct) {
            $product = $cartProduct->getProduct();
            $data['products'][] = [
                'product_id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $cartProduct->getQuantity()
            ];
        }

        return $data;
    }
}
