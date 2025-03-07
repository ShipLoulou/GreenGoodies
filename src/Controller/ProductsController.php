<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    public function __construct(
        private ProductsRepository $productsRepository,
        private SerializerInterface $serializerInterface,
        private ValidatorInterface $validatorInterface,
    ) {}

    #[Route('/', name: 'app_products')]
    public function showHome(): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $this->productsRepository->findAll()
        ]);
    }

    #[Route('/produits/{slug}', name: 'app_one_product')]
    public function showOneProduct($slug): Response
    {
        $product = $this->productsRepository->findOneBy(['slug' => $slug]);

        return $this->render('products/one_product.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function getAllProducts(): JsonResponse
    {
        $products = $this->productsRepository->findAll();

        // Modification du shéma des données pour respecter le cahier des charges
        $schema = [];

        foreach ($products as $product) {
            $schema[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'shortDescription' => $product->getBriefDescription(),
                'fullDescription' => $product->getDescription(),
                'price' => $product->getPriceFormated(),
                'picture' => $product->getImage()
            ];
        }

        $jsonProducts = $this->serializerInterface->serialize($schema, 'json');

        return new JsonResponse($jsonProducts, Response::HTTP_OK, [], true);
    }
}
