<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
       $this->entityManager = $entityManager;
    }
    #[Route('/mot-de-passe-oublier', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        if($this->getUser())
        {
           return  $this->redirectToRoute('app_home');
        }
        if($request->get('email')){
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if($user)
            {
                // 1 étape enregistrer en base de données la demande de reset_password de user,token, de createdAt
                $reset_password = new ResetPassword();
                $reset_password->setMyUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

              // 2 étape envoyer un lien par email l'utilisateur pour reinitialiser sont mot de passe
              $url = $this->generateUrl('app_update_password', ['token' =>$reset_password->getToken()]);
              $content = "Bonjour".$user->getFirstname()."<br/>vous avez demandé de reninitialiser votre mot de passe sur la Boutique Bretonne<br/><br/>"; 
              $content .= "Merci de bien vouloir cliquer sur ce lien pour <a href='".$url."' mettre à jour votre mot de passe </a>";

              $mail = new Mail();
              $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Réinitialiser votre mot de passe sur la Boutique Bretonne ',$content);
              $this->addFlash('notice', 'Vous allez recevoir un mail dans quelques instants avec la procédure pour réinitialser votre mot de passe');
            }else{
              $this->addFlash('notice', 'cette adresse email est inconnue');
            }

        }
        return $this->render('reset_password/index.html.twig');
    }
    #[Route('/modifier-mot-passe/{token}', name: 'app_update_password')]
    public function update(Request $request ,$token, UserPasswordHasherInterface $userPasswordHasher)
    {
         $reset_password = $this->entityManager->getRepository(ResetPasswordController::class)->findOneByToken($token);
         if(!$reset_password)
         {
            return $this->redirectToRoute('app_reset_password');
         }
         // laisser un delai de 2 heures
         $now = new \DateTimeImmutable();
         if($now > $reset_password->getCreatedAt()->modify('+ 2 hour')){
            $this->addFlash('notice', 'Votre de dmande de mot de password a expiré merci de la renouvelé');
            return $this->redirectToRoute('app_reset_password');
         }
         //rendre une vue avec mot de passe et la confirmation de mot de passe
         $form = $this->createForm(ResetPasswordType::class);
         $form->handleRequest($request);
          
         if($form->isSubmitted() && $form->isValid()){
            $new_password = $form->get('new_password')->getData();
            // enconder le mot de passe
            $password = $userPasswordHasher->hashPassword($reset_password->getUser(),$new_password);
            $reset_password->getUser()->setPassword($password);
            //pousser des données dans la base '
            $this->entityManager->flush();
            $this->addFlash('notice', 'votre mot de passe a été bien mise à jour');
            return $this->redirectToRoute("app_login");


         }
         

         return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
         ]);

         
         
         //redirection de l'utilisateur vers la page de connexion
         
         
    }
}
