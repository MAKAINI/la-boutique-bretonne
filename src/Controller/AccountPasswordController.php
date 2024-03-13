<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;

    }
    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $old_pwd = $form->get('old_password')->getData();
        
            $user = new  User();
           if($userPasswordHasher->isPasswordValid($user, $old_pwd));
           $new_old = $form->get('new_password')->getData();
           $password = $userPasswordHasher->hashPassword($user,$new_old, );
           $user->setPassword($password);
           $this->entityManager->flush();
           $notification = "votre mot de passe a été mise à jour";
        }else{
            $notification = "votre mot de passe actuel n'est pas le bon";
        }

        return $this->render('account/password.html.twig',[
            'form' => $form->createView(),
            'notification' => $notification
        ] );
       
    }
}
