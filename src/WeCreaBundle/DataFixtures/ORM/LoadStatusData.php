<?php

namespace WeCreaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WeCreaBundle\Entity\Status;

class LoadStatusData implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$allStatus = array(
			'waitSend',
			'send',
			'processingPayment',
			'payed',
			'processingDelivery',
			'send'
		);

		foreach ($allStatus as $status)
		{
			$s = new Status();
			$s->setName($status);
			$manager->persist($s);
		}

		$manager->flush();
	}
}