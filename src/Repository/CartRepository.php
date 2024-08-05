<?php

namespace App\Repository;

use App\Entity\CartProduct;
use App\Entity\Product;
use App\Service\Cart\Cart;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class CartRepository implements CartService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function addProduct(string $cartId, string $productId): void
    {
        $cart = $this->entityManager->find(\App\Entity\Cart::class, $cartId);
        $product = $this->entityManager->find(Product::class, $productId);

        if ($cart && $product) {
            $cart->addProduct($product);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }
    }

    public function removeProduct(string $cartId, string $productId): void
    {
        $cart = $this->entityManager->find(\App\Entity\Cart::class, $cartId);
        $product = $this->entityManager->find(Product::class, $productId);

        if ($cart && $product) {
            $cartProduct = $cart->getProduct($product);
            if ($cartProduct) {
                $quantity = $cartProduct->getQuantity();
                if ($quantity > 1) {
                    $cartProduct->setQuantity($quantity - 1);
                } else {
                    $this->entityManager->remove($cartProduct);
                }
                $this->entityManager->flush();
            }
        }
    }

    public function create(): Cart
    {
        $cart = new \App\Entity\Cart(Uuid::uuid4()->toString());

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }
}