<?php

namespace WeCreaBundle\Entity;

/**
 * Concept
 */
class Concept
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
     * @return Concept
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
     * @return Concept
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
     * @var \WeCreaBundle\Entity\Images
     */
    private $image;


    /**
     * Set image
     *
     * @param \WeCreaBundle\Entity\Images $image
     *
     * @return Concept
     */
    public function setImage(\WeCreaBundle\Entity\Images $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \WeCreaBundle\Entity\Images
     */
    public function getImage()
    {
        return $this->image;
    }
}
