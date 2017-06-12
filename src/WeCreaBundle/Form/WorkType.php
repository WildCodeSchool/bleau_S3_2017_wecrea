<?php

namespace WeCreaBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use WeCreaBundle\Entity\Nature;


class WorkType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', IntegerType::class, array(
                'required' => false
            ))
            ->add('title')
            ->add('description')
            ->add('technic')
            ->add('dimensions')
            ->add('dimensions2', TextType::class, array(
                'required' => false
            ))
            ->add('dimensions3', TextType::class, array(
                'required' => false
            ))
            ->add('dimensions4', TextType::class, array(
                'required' => false
            ))
            ->add('dimensions5', TextType::class, array(
                'required' => false
            ))
            ->add('weight')
            ->add('weight2', TextType::class, array(
                'required' => false
            ))
            ->add('weight3', TextType::class, array(
                'required' => false
            ))
            ->add('weight4', TextType::class, array(
                'required' => false
            ))
            ->add('weight5', TextType::class, array(
                'required' => false
            ))
            ->add('quantity')
            ->add('quantity2', IntegerType::class, array(
                'required' => false
            ))
            ->add('quantity3', IntegerType::class, array(
                'required' => false
            ))
            ->add('quantity4', IntegerType::class, array(
                'required' => false
            ))
            ->add('quantity5', IntegerType::class, array(
                'required' => false
            ))
            ->add('timelimit')
            ->add('price')
            ->add('price2', IntegerType::class, array(
                'required' => false
            ))
            ->add('price3', IntegerType::class, array(
                'required' => false
            ))
            ->add('price4', IntegerType::class, array(
                'required' => false
            ))
            ->add('price5', IntegerType::class, array(
                'required' => false
            ))
            ->add('nature', EntityType::class, array(
            'class' => Nature::class,
            'choice_label' => 'name',
            'multiple' => false
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WeCreaBundle\Entity\Work'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'wecreabundle_work';
    }


}
