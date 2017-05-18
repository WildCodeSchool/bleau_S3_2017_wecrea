<?php

namespace WeCreaBundle\Entity;

/**
 * Work
 */
class Work
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
    private $technic;

    /**
     * @var string
     */
    private $dimensions;

    /**
     * @var string
     */
    private $weight;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var string
     */
    private $timelimit;


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
     * @return Work
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
     * Set technic
     *
     * @param string $technic
     *
     * @return Work
     */
    public function setTechnic($technic)
    {
        $this->technic = $technic;

        return $this;
    }

    /**
     * Get technic
     *
     * @return string
     */
    public function getTechnic()
    {
        return $this->technic;
    }

    /**
     * Set dimensions
     *
     * @param string $dimensions
     *
     * @return Work
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * Get dimensions
     *
     * @return string
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return Work
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Work
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set timelimit
     *
     * @param string $timelimit
     *
     * @return Work
     */
    public function setTimelimit($timelimit)
    {
        $this->timelimit = $timelimit;

        return $this;
    }

    /**
     * Get timelimit
     *
     * @return string
     */
    public function getTimelimit()
    {
        return $this->timelimit;
    }
    /**
     * @var \WeCreaBundle\Entity\Artist
     */
    private $artist;

    /**
     * @var \WeCreaBundle\Entity\Nature
     */
    private $nature;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $images;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set artist
     *
     * @param \WeCreaBundle\Entity\Artist $artist
     *
     * @return Work
     */
    public function setArtist(\WeCreaBundle\Entity\Artist $artist = null)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return \WeCreaBundle\Entity\Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set nature
     *
     * @param \WeCreaBundle\Entity\Nature $nature
     *
     * @return Work
     */
    public function setNature(\WeCreaBundle\Entity\Nature $nature = null)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get nature
     *
     * @return \WeCreaBundle\Entity\Nature
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Add image
     *
     * @param \WeCreaBundle\Entity\Images $image
     *
     * @return Work
     */
    public function addImage(\WeCreaBundle\Entity\Images $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \WeCreaBundle\Entity\Images $image
     */
    public function removeImage(\WeCreaBundle\Entity\Images $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }
    /**
     * @var integer
     */
    private $price;


    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Work
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
}
