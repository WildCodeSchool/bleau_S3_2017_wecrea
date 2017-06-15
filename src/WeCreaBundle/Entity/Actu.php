<?php

namespace WeCreaBundle\Entity;

/**
 * Actu
 */
class Actu
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
    private $content;

    /**
     * @var \DateTime
     */
    private $date;

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
     * Set title
     *
     * @param string $title
     *
     * @return Actu
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
     * Set content
     *
     * @param string $content
     *
     * @return Actu
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Actu
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set images
     *
     * @param \WeCreaBundle\Entity\Images $images
     *
     * @return Actu
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
