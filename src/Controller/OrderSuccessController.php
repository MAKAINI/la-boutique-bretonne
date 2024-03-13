<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;
    private $adminUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator){
       $this->entityManager = $entityManager;
       $this->adminUrlGenerator = $adminUrlGenerator;
    }
    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index(Cart $cart ,$stripeSessionId): Response
    {   
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_home');
        }
        if(!$order->getState() == 0){
            //vider le panier
             $cart->remove();
            // modifier le statut isPaid de la commande en mettant 1
            $order->setState(1);
            $this->entityManager->flush();

             // envoyer un email à notre client pour confirmer la commande
            $email = new Mail();
                $content =  "Bonjour ".$order->getUser()->getFirstname()."<br/>Merci pour votre commande sur la boutique bretonne.<br/><br/>";
                $email->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(),'votre commande sur la Boutique Bretonne est bien validée',$content);
        }
        
        // envoyer un email à notre client pour confirmer la commande
        
        
        // afficher quelques informations de la commande
        return $this->render('order_success/index.html.twig',[
            'order' => $order,
        ]);
    }
}
