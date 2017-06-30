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
}
