<?php

namespace WeCreaBundle\Entity;

/**
 * WorkPurchased
 */
class WorkPurchased
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $caract;

    /**
     * @var int
     */
    private $quant;

    /**
     * @var int
     */
    private $price;

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
     * Set title
     *
     * @param string $title
     *
     * @return WorkPurchased
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set caract
     *
     * @param string $caract
     *
     * @return WorkPurchased
     */
    public function setCaract($caract)
    {
        $this->caract = $caract;

        return $this;
    }

    /**
     * Get caract
     *
     * @return string
     */
    public function getCaract()
    {
        return $this->caract;
    }

    /**
     * Set quant
     *
     * @param integer $quant
     *
     * @return WorkPurchased
     */
    public function setQuant($quant)
    {
        $this->quant = $quant;

        return $this;
    }

    /**
     * Get quant
     *
     * @return int
     */
    public function getQuant()
    {
        return $this->quant;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return WorkPurchased
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }
    /**
     * @var string
     */
    private $artist;


    /**
     * Set artist
     *
     * @param string $artist
     *
     * @return WorkPurchased
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }
    /**
     * @var integer
     */
    private $timeLimit;


    /**
     * Set timeLimit
     *
     * @param integer $timeLimit
     *
     * @return WorkPurchased
     */
    public function setTimeLimit($timeLimit)
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    /**
     * Get timeLimit
     *
     * @return integer
     */
    public function getTimeLimit()
    {
        return $this->timeLimit;
    }
}
