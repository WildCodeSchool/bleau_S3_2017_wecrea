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
}
