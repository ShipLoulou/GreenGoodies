<?php

namespace App\Controller;

use App\Entity\Orders;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Symfony\Component\Clock\now;

class OrdersController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    #[Route('/commandes/add/{price}', name: 'app_orders_add')]
    public function add($price)
    {
        $order = (new Orders())
            ->setValidationDate(now())
            ->setPrice($price)
            ->setUser($this->getUser());

        $this->em->persist($order);
        $this->em->flush();
    }
}
