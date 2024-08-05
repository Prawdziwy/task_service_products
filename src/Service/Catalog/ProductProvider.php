<?php

namespace App\Service\Catalog;

interface ProductProvider
{
    /**
     * @return Product[]
     */
    public function getProducts(int $page = 0, int $count = 3, string $sortBy = 'createdAt', string $order = 'DESC'): iterable;

    public function exists(string $productId): bool;

    public function getTotalCount(): int;
}
