<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;


    }
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $searchEmail = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$searchEmail){
                $password = $userPasswordHasher->hashPassword($user,$user->getPassword());
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $email = new Mail();
                $content =  "Bonjour ".$user->getFirstname()."<br/>Bienvenue sur la boutique dédié aux produits bretons.<br/><br/>";
                $email->send($user->getEmail(), $user->getFirstname(),'Bienvenue sur la Boutique Bretonne',$content);
                $notification= "Votre inscription s'est correctement deroulée.
                                Vous pouvez dès à présent connecter à votre compte";
            }else{
                $notification= "L'email que vous avez renseigné exite déjà";
            }
            
            
        }
        return $this->render('register/index.html.twig',[
            'form' => $form->createView(),
            'notification' => $notification
        ]);
        
    }
}
