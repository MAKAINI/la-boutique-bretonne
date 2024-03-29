<?php
namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class, [
                'label' => false,
                'required' => false,
                'attr'=>[
                    'placeholder' => 'Votre Recherche....',
                    'class'=>'form-control-sm',
                ]
            ])
            ->add('categories',Entity::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'multiple' => true,
                'expended' => true,
            ] )
            ->add('submit', SubmitType::class,[
                'attr'=>[
                    'label '=>'Filtrer',
                    'attr' =>[
                        'class' => 'btn-block btn-info'
                    ]
                ]
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
    public function getBlockPrefix()
    {
        return ' ';
        
    }

   
}

