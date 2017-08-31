<?php

namespace WeCreaBundle\Entity;

/**
 * Carrousel
 */
class Carrousel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $rout;

	/**
	 * @var string
	 */
	private $fontColor;

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
	 * Set fontColor
	 * @param $fontColor
	 * @return $this
	 */
    public function setFontColor($fontColor)
    {
    	$this->fontColor = $fontColor;
    	return $this;
    }

	/**
	 * Return fontColor
	 * @return string
	 */
    public function getFontColor()
    {
    	return $this->fontColor;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Carrousel
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Carrousel
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
     * Set rout
     *
     * @param string $rout
     *
     * @return Carrousel
     */
    public function setRout($rout)
    {
        $this->rout = $rout;

        return $this;
    }

    /**
     * Get rout
     *
     * @return string
     */
    public function getRout()
    {
        return $this->rout;
    }

    /**
     * @var \WeCreaBundle\Entity\Images
     */
    private $images;


    /**
     * Set images
     *
     * @param \WeCreaBundle\Entity\Images $images
     *
     * @return Carrousel
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
