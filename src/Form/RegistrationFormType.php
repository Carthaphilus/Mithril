<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('mail', TextType::class, ['attr' => ['class' => 'form-control']])
                ->add('agreeTerms', CheckboxType::class, [
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue([
                            'message' => 'Vous devez acceptez les termes.',
                                ]),
                    ],
                    'label' => 'Accepter les termes',
                    'label_attr' => ['class' => 'form-check-label'],
                    'attr'=>['class'=>'form-check-input']])
                ->add('plainPassword', PasswordType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrez un mot de passe',
                                ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit être composé de {{ limit }} caractéres',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                                ]),
                    ],
                    'attr' => ['class' => 'form-control'], 'label' => 'Mot de passe'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
