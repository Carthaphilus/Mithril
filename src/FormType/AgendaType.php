<?php

namespace App\FormType;

use App\Entity\Agenda;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of AgendaType
 *
 * @author jrobert
 */
class AgendaType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $option) {
        $builder
                ->add("id", HiddenType::class)
                ->add("nom", TextType::class, ['label' => 'Nom', 'attr' => ['class'=>'form-control', 'placeholder'=>'Nom']])
                ->add("image", FileType::class, ['label' => 'Image', 'attr' => ['class'=>'custom-file-input'], 'mapped'=>false])
                ->add("send", SubmitType::class, ['label' => "Enregistrer", 'attr'=>['class'=>'button-gradiant']]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Agenda::class
        ]);
    }
}
