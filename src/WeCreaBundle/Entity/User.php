<?php

namespace WeCreaBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    private $address1;

    /**
     * @var integer
     */
    private $zipCode1;

    /**
     * @var string
     */
    private $town1;

    /**
     * @var string
     */
    private $country1;

    /**
     * @var string
     */
    private $address2;

    /**
     * @var integer
     */
    private $zipCode2;

    /**
     * @var string
     */
    private $town2;

    /**
     * @var string
     */
    private $country2;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $firstname;


    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return User
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set zipCode1
     *
     * @param integer $zipCode1
     *
     * @return User
     */
    public function setZipCode1($zipCode1)
    {
        $this->zipCode1 = $zipCode1;

        return $this;
    }

    /**
     * Get zipCode1
     *
     * @return integer
     */
    public function getZipCode1()
    {
        return $this->zipCode1;
    }

    /**
     * Set town1
     *
     * @param string $town1
     *
     * @return User
     */
    public function setTown1($town1)
    {
        $this->town1 = $town1;

        return $this;
    }

    /**
     * Get town1
     *
     * @return string
     */
    public function getTown1()
    {
        return $this->town1;
    }

    /**
     * Set country1
     *
     * @param string $country1
     *
     * @return User
     */
    public function setCountry1($country1)
    {
        $this->country1 = $country1;

        return $this;
    }

    /**
     * Get country1
     *
     * @return string
     */
    public function getCountry1()
    {
        return $this->country1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return User
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set zipCode2
     *
     * @param integer $zipCode2
     *
     * @return User
     */
    public function setZipCode2($zipCode2)
    {
        $this->zipCode2 = $zipCode2;

        return $this;
    }

    /**
     * Get zipCode2
     *
     * @return integer
     */
    public function getZipCode2()
    {
        return $this->zipCode2;
    }

    /**
     * Set town2
     *
     * @param string $town2
     *
     * @return User
     */
    public function setTown2($town2)
    {
        $this->town2 = $town2;

        return $this;
    }

    /**
     * Get town2
     *
     * @return string
     */
    public function getTown2()
    {
        return $this->town2;
    }

    /**
     * Set country2
     *
     * @param string $country2
     *
     * @return User
     */
    public function setCountry2($country2)
    {
        $this->country2 = $country2;

        return $this;
    }

    /**
     * Get country2
     *
     * @return string
     */
    public function getCountry2()
    {
        return $this->country2;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
}
