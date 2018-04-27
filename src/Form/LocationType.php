<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 27/04/18
 * Time: 10:43
 */

namespace App\Form;


use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("description", EntityType::class, [
            'class' => Location::class,
            'choice_label' => 'description',
            'multiple' => false,
            'expanded' => false
        ])
        ->add('submit', SubmitType::class)
        ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class
        ]);
    }
}