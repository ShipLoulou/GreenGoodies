<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Symfony\Component\Clock\now;

class OrdersController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrdersRepository $ordersRepository
    ) {}

    #[Route('/commandes/add/{price}', name: 'app_orders_add', requirements: ['id' => "\d+"])]
    public function add($price)
    {
        $order = (new Orders())
            ->setValidationDate(now())
            ->setPrice($price)
            ->setUser($this->getUser());

        $this->em->persist($order);
        $this->em->flush();
    }

    #[Route('/commandes', name: 'app_orders_show')]
    public function show(): Response
    {
        $orders = $this->ordersRepository->findBy(["user" => $this->getUser()]);

        return $this->render('orders/index.html.twig', [
            'orders' => $orders
        ]);
    }
}
