<?php

namespace WeCreaBundle\Entity;

/**
 * Status
 */
class Status
{

	const WAITING_PAYMENT = 1;
	const REFUSED_PAYMENT = 3;
	const WAITING_AUTHORISATION = 2;
	const PAYED = 4;

	// GENERATED CODE

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Status
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
