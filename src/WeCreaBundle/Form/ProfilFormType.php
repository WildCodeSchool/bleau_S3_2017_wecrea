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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use WeCreaBundle\Entity\User;

class ProfilFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	if (
		    $options['data']->getAddress1() != $options['data']->getAddress2() or
		    $options['data']->getCountry1() != $options['data']->getCountry2() or
		    $options['data']->getTown1() != $options['data']->getTown2() or
		    $options['data']->getZipCode1() != $options['data']->getZipCode2()
	    ) {
	    	$status = true;
	    }
	    else{
		    $status = false;
	    }

        $builder
            ->add('name', TextType::class, array(
            	'label' => 'Nom'
            ))
            ->add('firstname', TextType::class, array(
            	'label' => 'Prénom'
            ))
            ->add('address1', TextType::class, array(
            	'label' => 'Adresse'
            ))
            ->add('zipCode1', IntegerType::class, array(
            	'label' => 'Code Postal'
            ))
            ->add('town1', TextType::class, array(
            	'label' => 'Ville'
            ))
            ->add('country1', TextType::class, array(
            	'label' => 'Pays'
            ))
            ->add('address2', TextType::class, array(
            	'label' => 'Adresse'
            ))
            ->add('zipCode2', IntegerType::class, array(
            	'label' => 'Code Postal'
            ))
            ->add('town2', TextType::class, array(
            	'label' => 'Ville'
            ))
            ->add('country2', TextType::class, array(
            	'label' => 'Pays'
            ));

    	if ($status == true){
		    $builder->add('sameadress', CheckboxType::class, array(
			    'label' => 'Mon adresse de facturation est différente de mon adresse de livraison',
			    'attr' => array(
				    'checked' => 'checked'
			    )
		    ));
	    }
	    elseif ($status == false){
		    $builder->add('sameadress', CheckboxType::class, array(
		    	'label' => 'Mon adresse de facturation est différente de mon adresse de livraison'
		    ));
	    }

        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profil';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
