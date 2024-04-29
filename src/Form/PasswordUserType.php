<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormError;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('actualPassword', PasswordType::class, [
                'label' => 'Votre mot de passe actuel ',
                'attr' => [
                    'placeholder' => 'Votre mot de passe actuel'
                ],
                'mapped' => false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 15
                    ])
                ],
                'first_options'  => [
                    'label' => 'Entrez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'mot de passe'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'confirmation mot de passe'
                    ]
                ],
                'mapped' => false,

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider le nouveau mot de passe',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                // die("OK l'event marche");
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                // dd($user->getPassword());
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

                // Récupération de la saisie utilisateur et le comparer au mot de passe en BDD
                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );

                // dd($isValid);

                if (!$isValid) {
                    $form->get('actualPassword')->addError(new FormError("la saisie du mot de passe actuel est erronée "));
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null,
        ]);
    }
}
