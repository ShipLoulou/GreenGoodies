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

        return $this->redirectToRoute('app_one_product', [
            'slug' => $product->getSlug()
        ]);
    }

    #[Route('/panier', name: 'app_cart_show')]
    public function show(SessionInterface $session)
    {
        $detailedCart = [];
        $total = 0;

        foreach ($session->get('cart', []) as $id => $quantity) {
            $product = $this->productsRepository->find($id);

            $detailedCart[] = [
                'product' => $product,
                'quantity' => $quantity
            ];

            $total += ($product->getPrice() * $quantity);
        }

        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total / 100
        ]);
    }

    #[Route('/panier/delete', name: 'app_cart_delete')]
    public function delete(SessionInterface $session)
    {

        $session->set('cart', []);

        $this->addFlash('success', 'Le panier à bien été vidé.');

        return $this->redirectToRoute('app_cart_show');
    }
}
