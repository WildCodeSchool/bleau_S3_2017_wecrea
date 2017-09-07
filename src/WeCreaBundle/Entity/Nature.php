<?php

namespace WeCreaBundle\Entity;

/**
 * Nature
 */
class Nature
{
	public $previousImage;

	// Generated

	/**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $fontColor;

    /**
     * @var \WeCreaBundle\Entity\Images
     */
    private $images;


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
     * @return Nature
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
     * Set fontColor
     *
     * @param string $fontColor
     *
     * @return Nature
     */
    public function setFontColor($fontColor)
    {
        $this->fontColor = $fontColor;

        return $this;
    }

    /**
     * Get fontColor
     *
     * @return string
     */
    public function getFontColor()
    {
        return $this->fontColor;
    }

    /**
     * Set images
     *
     * @param \WeCreaBundle\Entity\Images $images
     *
     * @return Nature
     */
    public function setImages(\WeCreaBundle\Entity\Images $images = null)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get images
     *
     * @return \WeCreaBundle\Entity\Images
     */
    public function getImages()
    {
        return $this->images;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $works;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->works = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add work
     *
     * @param \WeCreaBundle\Entity\Work $work
     *
     * @return Nature
     */
    public function addWork(\WeCreaBundle\Entity\Work $work)
    {
        $this->works[] = $work;

        return $this;
    }

    /**
     * Remove work
     *
     * @param \WeCreaBundle\Entity\Work $work
     */
    public function removeWork(\WeCreaBundle\Entity\Work $work)
    {
        $this->works->removeElement($work);
    }

    /**
     * Get works
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorks()
    {
        return $this->works;
    }
}
