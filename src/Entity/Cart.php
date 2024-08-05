<?php

namespace App\Entity;

use \App\Entity\CartProduct;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Cart implements \App\Service\Cart\Cart
{
    public const CAPACITY = 3;
    public const MAX_QUANTITY_OF_PRODUCT = 2;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\OneToMany(targetEntity: CartProduct::class, mappedBy: 'cart', cascade: ['persist', 'remove'])]
    private Collection $products;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
        $this->products = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTotalPrice(): int
    {
        return array_reduce(
            $this->products->toArray(),
            static fn(int $total, CartProduct $cartProduct): int => $total + $cartProduct->getProduct()->getPrice() * $cartProduct->getQuantity(),
            0
        );
    }

    #[Pure]
    public function isFull(): bool
    {
        $totalQuantity = array_reduce(
            $this->products->toArray(),
            fn($sum, CartProduct $cartProduct) => $sum + $cartProduct->getQuantity(),
            0
        );

        return $totalQuantity >= self::CAPACITY;
    }

    #[Pure]
    public function isMaximumQuantityOfProduct(\App\Entity\Product $product): bool
    {
        $cartProduct = $this->getProduct($product);

        return $cartProduct ? $cartProduct->getQuantity() >= self::MAX_QUANTITY_OF_PRODUCT : false;
    }

    public function getProducts(): iterable
    {
        return $this->products->getIterator();
    }

    #[Pure]
    public function hasProduct(\App\Entity\Product $product): bool
    {
        return $this->products->exists(function ($key, CartProduct $cartProduct) use ($product) {
            return $cartProduct->getProduct() === $product;
        });
    }

    public function addProduct(\App\Entity\Product $product): void
    {
        $cartProduct = $this->getProduct($product);

        if ($cartProduct) {
            $cartProduct->setQuantity($cartProduct->getQuantity() + 1);
        } else {
            $this->products->add(new CartProduct($this, $product, 1));
        }
    }

    public function removeProduct(\App\Entity\Product $product): bool
    {
        $cartProduct = $this->getProduct($product);
        if (!$cartProduct) {
            return false;
        }

        $quantity = $cartProduct->getQuantity();
        if ($quantity <= 1) {
            $this->products->removeElement($cartProduct);
            return false;
        }

        $cartProduct->setQuantity($quantity - 1);
        return true;
    }

    public function getProduct(\App\Entity\Product $product): ?CartProduct
    {
        foreach ($this->products as $cartProduct) {
            if ($cartProduct->getProduct() === $product) {
                return $cartProduct;
            }
        }

        return null;
    }
}
