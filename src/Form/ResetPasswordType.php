<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label'=> 'Mon nouveau Mot de Passe',
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
                    'label'=> "Mettre à jour mot de passe",
                    'attr'=> [
                        'class' => 'btn-block btn-info',
                    ]
                    
                    ])
          
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
