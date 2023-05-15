<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class,[
                "attr" =>[
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Prénom'
            ])
            ->add('firstname',TextType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Nom'
            ])
            ->add('email', EmailType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlength' => '180',
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form-label mt-4']
            ])
            ->add('subject', TextType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlength' => '100',
                ],
                'label'=> 'Sujet',
                'label_attr' =>[
                    'class' => 'form-label mt-4'
                ]
               
            ])
            ->add('message', TextareaType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    
                ],
                'label' => 'Votre message',
                'label_attr' => [
                    'class' => 'form-label mt-4']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
