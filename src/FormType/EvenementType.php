<?php

namespace App\FormType;

use App\Entity\Agenda;
use App\Entity\Evenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of EvenementType
 *
 * @author jrobert
 */
class EvenementType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $option) {
        $builder
                ->add("id", HiddenType::class)
                ->add("agenda", EntityType::class, [
                    'class' => Agenda::class,
                    'choice_label' => function ($Agenda) {
                        return $Agenda->getNom();
                    },
                    'attr' => ['class' => 'form-control']
                ])
                ->add("libelle", TextType::class, ['attr' => ['placeholder' => 'Nom de l\'évenement', 'class' => 'form-control']])
//                ->add("dateDeb", TextType::class, ['mapped' => false])
//                ->add("dateFin", TextType::class, ['mapped' => false])
                ->add("note", TextareaType::class, ['attr' => ['placeholder' => 'Note', 'class' => 'form-control', 'rows' => '1']])
                ->add("lieux", TextType::class, ['attr' => ['placeholder' => 'Lieux', 'class' => 'form-control']])
//                ->add("taches", TextareaType::class, ['attr' => ['placeholder' => 'Tâches', 'class' => 'form-control','rows'=>'1']])
                ->add("couleur", ChoiceType::class, [
                    'choices' => [
                        'bleu' => '#cce5ff',
                        'gris' => '#e2e3e5',
                        'vert' => '#d4edda',
                        'rouge' => '#f8d7da',
                        'jaune' => '#fff3cd',
                        'noir' => '#d6d8d9',
                    ],
                    'attr' => ['class' => 'form-control'],
                ])
                ->add("send", SubmitType::class, ['label' => "Enregistrer", 'attr' => ['value' => 'enreg', 'class' => 'button-gradiant']]);
//              ->add("send2", SubmitType::class, ['label' => "Supprimer", 'attr'=>['value'=>'supp']]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
            'allow_extra_fields' => true
        ]);
    }

}
