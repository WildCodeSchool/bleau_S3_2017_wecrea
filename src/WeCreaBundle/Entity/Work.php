<?php

namespace WeCreaBundle\Entity;

/**
 * Work
 */
class Work
{
	public $quantity;

	// GENERATED CODE

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $technic;

    /**
     * @var integer
     */
    private $timelimit;

	/**
	 * @var string
	 */
	private $zoom;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $caracts;

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
        $this->caracts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set timelimit
     *
     * @param integer $timelimit
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
     * @return integer
     */
    public function getTimelimit()
    {
        return $this->timelimit;
    }

    /**
     * Add caract
     *
     * @param \WeCreaBundle\Entity\Caract $caract
     *
     * @return Work
     */
    public function addCaract(\WeCreaBundle\Entity\Caract $caract)
    {
        $this->caracts[] = $caract;

        return $this;
    }

    /**
     * Remove caract
     *
     * @param \WeCreaBundle\Entity\Caract $caract
     */
    public function removeCaract(\WeCreaBundle\Entity\Caract $caract)
    {
        $this->caracts->removeElement($caract);
    }

    /**
     * Get caracts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCaracts()
    {
        return $this->caracts;
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
     * Set zoom
     *
     * @param boolean $zoom
     *
     * @return Work
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * Get zoom
     *
     * @return boolean
     */
    public function getZoom()
    {
        return $this->zoom;
    }
}
