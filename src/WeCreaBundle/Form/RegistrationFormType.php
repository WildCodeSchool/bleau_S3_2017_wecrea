<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WeCreaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('firstname', TextType::class)
            ->add('address1', TextType::class)
            ->add('zipCode1', TextType::class)
            ->add('town1', TextType::class)
            ->add('country1', TextType::class)
            ->add('address2', TextType::class, array(
            	'required' => false,
            ))
            ->add('zipCode2', TextType::class, array(
            	'required' => false,
            ))
            ->add('town2', TextType::class, array(
            	'required' => false,
            ))
            ->add('country2', TextType::class, array(
            	'required' => false,
            ))
	        ->add('sameadress', ChoiceType::class, array(
		        'choices'  => array(
			        'Mon adresse de facturation est différente de mon adresse de livraison' => true),
		        'expanded' => true,
		        'multiple' => true
	        ));



    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
