<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(private ProductsRepository $productsRepository) {}

    #[Route('/panier/add/{id}', name: 'app_cart_add', requirements: ['id' => "\d+"])]
    public function add($id, SessionInterface $session): Response
    {
        $product = $this->productsRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'exite pas.");
        }

        $cart = $session->get('cart', []);

        if (array_key_exists($id, $cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        $this->addFlash('success', 'Le produit à bien été ajouté au panier.');
        // dd($cart);

        return $this->redirectToRoute('app_one_product', [
            'slug' => $product->getSlug()
        ]);
    }
}
