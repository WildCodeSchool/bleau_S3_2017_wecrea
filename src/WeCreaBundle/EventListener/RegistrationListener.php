<?php

// src/Acme/UserBundle/EventListener/PasswordResettingListener.php

namespace WeCreaBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class RegistrationListener implements EventSubscriberInterface
{
	private $router;

	public function __construct(UrlGeneratorInterface $router)
	{
		$this->router = $router;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
			FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
		);
	}

	public function onRegistrationSuccess(FormEvent $event)
	{
		$data = $event->getForm()->getData();

		if (isset($data->sameadress[0]))
		{
			return $event;
		}
		else
		{
			$data->setAddress2($data->getAddress1());
			$data->setCountry2($data->getCountry1());
			$data->setTown2($data->getTown1());
			$data->setZipCode2($data->getZipCode1());

			return $event;
		}
	}
}