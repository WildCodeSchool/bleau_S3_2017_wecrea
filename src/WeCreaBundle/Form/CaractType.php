<?php

namespace WeCreaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CaractType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dimension', TextType::class, array(
                "label" => "Dimensions (préciser l'unité)"
            ))
            ->add('weigth', TextType::class, array(
                "label" => "Poids (préciser l'unité)",
	            'required' => false
            ))
            ->add('price', IntegerType::class, array(
                "label" => "Prix (sans sigle)"
            ))
            ->add('quantity', IntegerType::class, array(
                "label" => "Quantité disponible"
            ))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WeCreaBundle\Entity\Caract'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'wecreabundle_caract';
    }


}
