<?php

namespace WeCreaBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NatureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('fontColor', TextType::class, array(
	        	'attr' => array(
	        		'class' => 'colorpicker'
		        )
	        ))
	        ->add('name', TextType::class)
	        ->add('images', ImagesType::class)
	        ->add('submit', SubmitType::class, array(
	        	'label' => 'Valider',
		        'attr' => array(
		        	'class' => "btn waves-effect waves-light right black submitButtonNature"
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
            'data_class' => 'WeCreaBundle\Entity\Nature'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'wecreabundle_nature';
    }


}
