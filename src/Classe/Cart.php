<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    public function add($product)
    {
        // dd($product);

        // Appeler la session Cart de symfony
        $cart = $this->requestStack->getSession()->get('cart');
        // dd($session);

        // Ajouter qty +1 à mon produit
        if ($cart[$product->getId()]) {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1
            ];
        }

        // Créer la session Cart
        $this->requestStack->getSession()->set('cart', $cart);

        // dd($this->requestStack->getSession()->get('cart'));
    }


    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}
