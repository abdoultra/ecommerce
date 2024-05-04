<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('actualPassword', PasswordType::class, [
                'label'=> "Votre mot de pass actuel",
                'attr'=> [
                    'placeholder'=> "Donnez votre mot de passe actuel"
                ],
                'mapped' => false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length ([
                        'min'=>4,
                        'max'=>30,
                        ])],
                'first_options'  => [
                    'label' => 'Votre nouveau mot de passe',
                    'attr'=> [
                        'placeholder'=> "Choississez votre nouveau mot de passe"
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmer votre nouveau Password',
                    'attr'=> [
                        'placeholder'=> "Confirmez votre nouveau mot de passe"
                    ]
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label'=> "Mettre à jour mon mot de passe",
                'attr'=> [
                    'class'=> "btn btn-success"
                ] 
            ])
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                //recuperer le mot de pass saisi par l'user et le comparer au mdp en bdd
                $actualPwd = $form->get('actualPassword')->getData();
                $isValid = $hashedPassword = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );
                //Recuper le mot de pass dans la bdd    $actualPwdDatabase = $user->getPassword();
                //si mdp different envoyer une erreur 
                if(!$isValid) {
                    $form->get('actualPassword')->addError(new FormError("Votre ancien mot de passe n'est pas conforne"));
                }
            });
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
