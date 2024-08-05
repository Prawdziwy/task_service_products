<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cart_products')]
class CartProduct
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'cartProducts')]
    #[ORM\JoinColumn(name: 'cart_id', referencedColumnName: 'id')]
    private Cart $cart;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 1;

    public function __construct(Cart $cart, Product $product, int $quantity)
    {
        $this->cart = $cart;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
