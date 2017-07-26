<?php

namespace WeCreaBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LegalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mention', CKEditorType::class, array(
	            'config_name' => 'my_config',
                'label' => 'Mentions légales'
            ))
            ->add('defiscalisation', CKEditorType::class, array(
	            'config_name' => 'my_config',
                'label' => 'Défiscalisation'
            ))
            ->add('cgu', CKEditorType::class, array(
	            'config_name' => 'my_config',
                'label' => 'Condition général d\'utilisation'
            ))
            ->add('cgv', CKEditorType::class, array(
	            'config_name' => 'my_config',
                'label' => 'Condition général de vente'
            ))
            ->add('tva', NumberType::class, array(
                'label' => 'TVA ( en % ) : ',
                'attr' => array(
                    'style' => 'width:60px;'
                )
            ))
	        ->add('facebook', TextType::class, array(
	        	'required' => false
	        ))
	        ->add('twitter', TextType::class, array(
	        	'required' => false
	        ))
	        ->add('instagram', TextType::class, array(
	        	'required' => false
	        ))
	        ->add('youtube', TextType::class, array(
	        	'required' => false
	        ))
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn black center'
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
            'data_class' => 'WeCreaBundle\Entity\Legal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'wecreabundle_legal';
    }


}
