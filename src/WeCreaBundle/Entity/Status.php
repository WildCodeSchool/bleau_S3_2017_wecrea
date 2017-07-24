<?php

namespace WeCreaBundle\Entity;

/**
 * Status
 */
class Status
{
	// TODO: Modify name and value of constant
	const NAME = 1;
	const NAME2 = 2;
	const NAME3 = 3;
	const NAME4 = 4;

	// Generated Code

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;


    /**
     * Get id
     *
     * @return int
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
