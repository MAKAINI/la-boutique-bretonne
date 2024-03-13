<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'disabled' => true,
                'label' => 'Mon adresse email',
            ])
            ->add('firstname', TextType::class,[
                'disabled' => true,
                'label' => 'Mon prénom'
            ])
            ->add('lastname', TextType::class,[
                'disabled' => true,
                'label' =>'Mon nom',
            ])
            ->add('old_password' , PasswordType::class,[
                'label' => 'Mon mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre password actuel',
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label'=> 'Mon nouveau Mot de Passe',
                'mapped' => false,
                'required' => true,
                'first_options'=>[
                    'label'=>'Nom nouveau mot de Passe',
                    'attr'=>['placeholder' => 'Nom nouveau mot de Passe'],
                ],
                'second_options'=>[
                    'label'=>'Confirmez votre nouveau mot de Passe',
                    'attr'=>['placeholder' => 'Veuillez confirmer votre Mot de Passe']
                    ]
                
                
                ])
                ->add('submit', SubmitType::class, [
                    'label'=> "Mettre à jour",
                    
                    ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
