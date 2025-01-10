<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductsController extends AbstractController
{
    public function __construct(
        private ProductsRepository $productsRepository,
    ) {}

    #[Route('/', name: 'app_products')]
    public function showHome(): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $this->productsRepository->findAll()
        ]);
    }
}
