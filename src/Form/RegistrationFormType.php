<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => false
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'required' => false
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les CGU de GreenGoodies',
                'row_attr' => [
                    'class' => 'inpuCheckbox'
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisation',
                    ]),
                ],
                'required' => false
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Champs mot de passe obligatoire.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le champs mot de passe doit comptenir au minimum {{ limit }} caractères.',
                        'max' => 255
                    ]),
                ],
                'required' => false
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmation mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Champs conformation mot de passe obligatoire.',
                    ]),
                ],
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['id' => 'formRegisterJS']
        ]);
    }
}
