<?php

namespace WeCreaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WeCreaBundle\Entity\Images;
use WeCreaBundle\WeCreaBundle;

class CarrouselType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class)
            ->add('title', TextType::class)
            ->add('rout', TextType::class)
            ->add('images', ImagesType::class)
	        ->add('fontColor', TextType::class, array(
	        	'label' => 'Couleur de texte',
		        'attr' => array(
			        'class' => 'colorpicker',
                    'style' => 'background-color: rgb(255, 255, 255);'
		        )
	        ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WeCreaBundle\Entity\Carrousel'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'wecreabundle_carrousel';
    }


}
