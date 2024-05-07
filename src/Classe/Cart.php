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
        if (isset($cart[$product->getId()])) {
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

    public function decrease($id)
    {
        // Appeler la session Cart de symfony
        $cart = $this->requestStack->getSession()->get('cart');

        // Décrémenter de 1 ma quantité de produit
        if ($cart[$id]['qty'] > 1) {
            $cart[$id]['qty']--;
        } else {
            unset($cart[$id]);
        }

        // Créer la session Cart mis à jours
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function remove()
    {

        return $this->requestStack->getSession()->remove('cart');
    }

    public function fullQuantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $quantity = 0;

        if (!isset($cart)) {
            return $quantity;
        }

        foreach ($cart as $product) {
            // dd($product);
            $quantity = $quantity + $product['qty'];
        }
        // dd($cart);
        // dd($quantity);
        return ($quantity);
    }

    public function getTotalWt()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $price = 0;

        if (!isset($cart)) {
            return $price;
        }

        foreach ($cart as $product) {
            $price = $price + ($product['object']->getPriceWt() * $product['qty']);
        }
        return ($price);
    }

    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }
}
