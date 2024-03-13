<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountOrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/mes-commandes', name: 'app_account_order')]
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccesOrders($this->getUser());
        
        return $this->render('account/order.html.twig', [
            'order' => $orders,
        ]);
    }

    #[Route('/compte/mes-commandes{reference}', name: 'app_account_order_show')]
    public function show($reference): Response
    {
        
        $orders = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);
         $myuser = $orders->getMyUser();
        if(!$orders || $orders->getMyUser() !== $orders->getMyUser()){
            return $this->redirectToRoute('app_account_order');
        }
        
        return $this->render('account/order_show.html.twig', [
            'order' => $orders,
        ]);
    }

}

