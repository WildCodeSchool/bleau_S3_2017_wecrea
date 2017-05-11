<?php

namespace WeCreaBundle\Form;

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
            ->add('technic')
            ->add('dimensions')
            ->add('weight')
            ->add('quantity')
            ->add('timelimit')
            ->add('price')
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
