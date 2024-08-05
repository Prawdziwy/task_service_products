<?php

namespace App\Messenger;

use App\Messenger\EditProductInCatalog;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EditProductInCatalogHandler implements MessageHandlerInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function __invoke(EditProductInCatalog $message): void
    {
        $product = $this->entityManager->find(Product::class, $message->productId);

        if ($product) {
            $product->setName($message->name);
            $product->setPrice($message->price);
            $this->entityManager->flush();
        }
    }
}
