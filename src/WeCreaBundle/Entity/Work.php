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
    /**
     * @var string
     */
    private $description;


    /**
     * Set description
     *
     * @param string $description
     *
     * @return Work
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @var string
     */
    private $dimensions2;

    /**
     * @var string
     */
    private $dimensions3;

    /**
     * @var string
     */
    private $dimensions4;

    /**
     * @var string
     */
    private $dimensions5;

    /**
     * @var string
     */
    private $weight2;

    /**
     * @var string
     */
    private $weight3;

    /**
     * @var string
     */
    private $weight4;

    /**
     * @var string
     */
    private $weight5;

    /**
     * @var integer
     */
    private $quantity2;

    /**
     * @var integer
     */
    private $quantity3;

    /**
     * @var integer
     */
    private $quantity4;

    /**
     * @var integer
     */
    private $quantity5;

    /**
     * @var integer
     */
    private $price2;

    /**
     * @var integer
     */
    private $price3;

    /**
     * @var integer
     */
    private $price4;

    /**
     * @var integer
     */
    private $price5;


    /**
     * Set dimensions2
     *
     * @param string $dimensions2
     *
     * @return Work
     */
    public function setDimensions2($dimensions2)
    {
        $this->dimensions2 = $dimensions2;

        return $this;
    }

    /**
     * Get dimensions2
     *
     * @return string
     */
    public function getDimensions2()
    {
        return $this->dimensions2;
    }

    /**
     * Set dimensions3
     *
     * @param string $dimensions3
     *
     * @return Work
     */
    public function setDimensions3($dimensions3)
    {
        $this->dimensions3 = $dimensions3;

        return $this;
    }

    /**
     * Get dimensions3
     *
     * @return string
     */
    public function getDimensions3()
    {
        return $this->dimensions3;
    }

    /**
     * Set dimensions4
     *
     * @param string $dimensions4
     *
     * @return Work
     */
    public function setDimensions4($dimensions4)
    {
        $this->dimensions4 = $dimensions4;

        return $this;
    }

    /**
     * Get dimensions4
     *
     * @return string
     */
    public function getDimensions4()
    {
        return $this->dimensions4;
    }

    /**
     * Set dimensions5
     *
     * @param string $dimensions5
     *
     * @return Work
     */
    public function setDimensions5($dimensions5)
    {
        $this->dimensions5 = $dimensions5;

        return $this;
    }

    /**
     * Get dimensions5
     *
     * @return string
     */
    public function getDimensions5()
    {
        return $this->dimensions5;
    }

    /**
     * Set weight2
     *
     * @param string $weight2
     *
     * @return Work
     */
    public function setWeight2($weight2)
    {
        $this->weight2 = $weight2;

        return $this;
    }

    /**
     * Get weight2
     *
     * @return string
     */
    public function getWeight2()
    {
        return $this->weight2;
    }

    /**
     * Set weight3
     *
     * @param string $weight3
     *
     * @return Work
     */
    public function setWeight3($weight3)
    {
        $this->weight3 = $weight3;

        return $this;
    }

    /**
     * Get weight3
     *
     * @return string
     */
    public function getWeight3()
    {
        return $this->weight3;
    }

    /**
     * Set weight4
     *
     * @param string $weight4
     *
     * @return Work
     */
    public function setWeight4($weight4)
    {
        $this->weight4 = $weight4;

        return $this;
    }

    /**
     * Get weight4
     *
     * @return string
     */
    public function getWeight4()
    {
        return $this->weight4;
    }

    /**
     * Set weight5
     *
     * @param string $weight5
     *
     * @return Work
     */
    public function setWeight5($weight5)
    {
        $this->weight5 = $weight5;

        return $this;
    }

    /**
     * Get weight5
     *
     * @return string
     */
    public function getWeight5()
    {
        return $this->weight5;
    }

    /**
     * Set quantity2
     *
     * @param integer $quantity2
     *
     * @return Work
     */
    public function setQuantity2($quantity2)
    {
        $this->quantity2 = $quantity2;

        return $this;
    }

    /**
     * Get quantity2
     *
     * @return integer
     */
    public function getQuantity2()
    {
        return $this->quantity2;
    }

    /**
     * Set quantity3
     *
     * @param integer $quantity3
     *
     * @return Work
     */
    public function setQuantity3($quantity3)
    {
        $this->quantity3 = $quantity3;

        return $this;
    }

    /**
     * Get quantity3
     *
     * @return integer
     */
    public function getQuantity3()
    {
        return $this->quantity3;
    }

    /**
     * Set quantity4
     *
     * @param integer $quantity4
     *
     * @return Work
     */
    public function setQuantity4($quantity4)
    {
        $this->quantity4 = $quantity4;

        return $this;
    }

    /**
     * Get quantity4
     *
     * @return integer
     */
    public function getQuantity4()
    {
        return $this->quantity4;
    }

    /**
     * Set quantity5
     *
     * @param integer $quantity5
     *
     * @return Work
     */
    public function setQuantity5($quantity5)
    {
        $this->quantity5 = $quantity5;

        return $this;
    }

    /**
     * Get quantity5
     *
     * @return integer
     */
    public function getQuantity5()
    {
        return $this->quantity5;
    }

    /**
     * Set price2
     *
     * @param integer $price2
     *
     * @return Work
     */
    public function setPrice2($price2)
    {
        $this->price2 = $price2;

        return $this;
    }

    /**
     * Get price2
     *
     * @return integer
     */
    public function getPrice2()
    {
        return $this->price2;
    }

    /**
     * Set price3
     *
     * @param integer $price3
     *
     * @return Work
     */
    public function setPrice3($price3)
    {
        $this->price3 = $price3;

        return $this;
    }

    /**
     * Get price3
     *
     * @return integer
     */
    public function getPrice3()
    {
        return $this->price3;
    }

    /**
     * Set price4
     *
     * @param integer $price4
     *
     * @return Work
     */
    public function setPrice4($price4)
    {
        $this->price4 = $price4;

        return $this;
    }

    /**
     * Get price4
     *
     * @return integer
     */
    public function getPrice4()
    {
        return $this->price4;
    }

    /**
     * Set price5
     *
     * @param integer $price5
     *
     * @return Work
     */
    public function setPrice5($price5)
    {
        $this->price5 = $price5;

        return $this;
    }

    /**
     * Get price5
     *
     * @return integer
     */
    public function getPrice5()
    {
        return $this->price5;
    }
}
