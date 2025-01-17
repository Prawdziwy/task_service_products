<?php

namespace App\Service\Cart;

use App\Service\Catalog\Product;

interface Cart
{
    public function getId(): string;
    public function getTotalPrice(): int;
    public function isFull(): bool;
    /**
     * @return \App\Entity\CartProduct[]
     */
    public function getProducts(): iterable;
}
