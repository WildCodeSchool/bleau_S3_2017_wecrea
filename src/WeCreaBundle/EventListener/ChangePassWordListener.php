<?php

namespace WeCreaBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class ChangePassWordListener implements EventSubscriberInterface {
	private $router;

	public function __construct(UrlGeneratorInterface $router) {
		$this->router = $router;
	}

	public static function getSubscribedEvents() {
		return [
			FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onPasswordChangeComplete',
		];
	}

	public function onPasswordChangeComplete(FormEvent $event) {
		$url = $this->router->generate('we_crea_user_profil');
		$event->setResponse(new RedirectResponse($url));
	}
}