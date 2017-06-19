<?php

namespace WeCreaBundle\Entity;

/**
 * Caract
 */
class Caract
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $dimension;

    /**
     * @var string
     */
    private $weigth;

    /**
     * @var integer
     */
    private $price;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var \WeCreaBundle\Entity\Work
     */
    private $work;


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
     * Set dimension
     *
     * @param string $dimension
     *
     * @return Caract
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get dimension
     *
     * @return string
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Set weigth
     *
     * @param string $weigth
     *
     * @return Caract
     */
    public function setWeigth($weigth)
    {
        $this->weigth = $weigth;

        return $this;
    }

    /**
     * Get weigth
     *
     * @return string
     */
    public function getWeigth()
    {
        return $this->weigth;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Caract
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Caract
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set work
     *
     * @param \WeCreaBundle\Entity\Work $work
     *
     * @return Caract
     */
    public function setWork(\WeCreaBundle\Entity\Work $work = null)
    {
        $this->work = $work;

        return $this;
    }

    /**
     * Get work
     *
     * @return \WeCreaBundle\Entity\Work
     */
    public function getWork()
    {
        return $this->work;
    }
}
