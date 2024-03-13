<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('notice',"Merci de nous contacter. Notre équipe va vous répondre dans une meilleur delai");

        }
        return $this->render('contact/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
