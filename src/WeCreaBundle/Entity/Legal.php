<?php

namespace WeCreaBundle\Entity;

/**
 * Legal
 */
class Legal
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $mention;

    /**
     * @var int
     */
    private $tva;


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
     * Set mention
     *
     * @param string $mention
     *
     * @return Legal
     */
    public function setMention($mention)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * Get mention
     *
     * @return string
     */
    public function getMention()
    {
        return $this->mention;
    }

    /**
     * Set tva
     *
     * @param integer $tva
     *
     * @return Legal
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return int
     */
    public function getTva()
    {
        return $this->tva;
    }
    /**
     * @var string
     */
    private $defiscalisation;


    /**
     * Set defiscalisation
     *
     * @param string $defiscalisation
     *
     * @return Legal
     */
    public function setDefiscalisation($defiscalisation)
    {
        $this->defiscalisation = $defiscalisation;

        return $this;
    }

    /**
     * Get defiscalisation
     *
     * @return string
     */
    public function getDefiscalisation()
    {
        return $this->defiscalisation;
    }
    /**
     * @var string
     */
    private $cgv;

    /**
     * @var string
     */
    private $cgu;


    /**
     * Set cgv
     *
     * @param string $cgv
     *
     * @return Legal
     */
    public function setCgv($cgv)
    {
        $this->cgv = $cgv;

        return $this;
    }

    /**
     * Get cgv
     *
     * @return string
     */
    public function getCgv()
    {
        return $this->cgv;
    }

    /**
     * Set cgu
     *
     * @param string $cgu
     *
     * @return Legal
     */
    public function setCgu($cgu)
    {
        $this->cgu = $cgu;

        return $this;
    }

    /**
     * Get cgu
     *
     * @return string
     */
    public function getCgu()
    {
        return $this->cgu;
    }
    /**
     * @var string
     */
    private $facebook;

    /**
     * @var string
     */
    private $twitter;

    /**
     * @var string
     */
    private $instagram;

    /**
     * @var string
     */
    private $youtube;


    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return Legal
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     *
     * @return Legal
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set instagram
     *
     * @param string $instagram
     *
     * @return Legal
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return string
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Set youtube
     *
     * @param string $youtube
     *
     * @return Legal
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * Get youtube
     *
     * @return string
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * @var string
     */
    private $returnWorkText;


    /**
     * Set returnWorkText
     *
     * @param string $returnWorkText
     *
     * @return Legal
     */
    public function setReturnWorkText($returnWorkText)
    {
        $this->returnWorkText = $returnWorkText;

        return $this;
    }

    /**
     * Get returnWorkText
     *
     * @return string
     */
    public function getReturnWorkText()
    {
        return $this->returnWorkText;
    }
}
