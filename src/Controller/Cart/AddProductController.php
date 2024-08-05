<?php

namespace App\Controller\Cart;

use App\Entity\Cart;
use App\Entity\Product;
use App\Messenger\AddProductToCart;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart/{cart}/{product}", methods={"PUT"}, name="cart-add-product")
 */
class AddProductController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(private ErrorBuilder $errorBuilder) { }

    public function __invoke(Cart $cart, Product $product): Response
    {
        try {
            if ($cart->isFull()) {
                throw new \Exception('Cart is full.');
            } elseif ($cart->isMaximumQuantityOfProduct($product)) {
                throw new \Exception('You have maximum amount of this product in this cart.');
            }

            $this->dispatch(new AddProductToCart($cart->getId(), $product->getId()));

            return new Response('', Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return new JsonResponse(
                $this->errorBuilder->__invoke($e->getMessage()),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
