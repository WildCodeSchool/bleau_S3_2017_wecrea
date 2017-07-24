<?php

namespace WeCreaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WeCreaBundle\Entity\Status;

class LoadStatusData implements FixtureInterface
{
	public function load(ObjectManager $manager)
	{
		// TODO: Modify name of constant
		$allStatus = array(
			Status::NAME,
			Status::NAME2,
			Status::NAME3,
			Status::NAME4
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