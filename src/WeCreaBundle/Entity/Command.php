<?php

namespace WeCreaBundle\Entity;

/**
 * Command
 */
class Command
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $addressfact;

    /**
     * @var integer
     */
    private $zipCodefact;

    /**
     * @var string
     */
    private $townfact;

    /**
     * @var string
     */
    private $addressdel;

    /**
     * @var integer
     */
    private $zipCodedel;

    /**
     * @var string
     */
    private $towndel;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $mail;

    /**
     * @var string
     */
    private $nb;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Command
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
     * Set addressfact
     *
     * @param string $addressfact
     *
     * @return Command
     */
    public function setAddressfact($addressfact)
    {
        $this->addressfact = $addressfact;

        return $this;
    }

    /**
     * Get addressfact
     *
     * @return string
     */
    public function getAddressfact()
    {
        return $this->addressfact;
    }

    /**
     * Set zipCodefact
     *
     * @param integer $zipCodefact
     *
     * @return Command
     */
    public function setZipCodefact($zipCodefact)
    {
        $this->zipCodefact = $zipCodefact;

        return $this;
    }

    /**
     * Get zipCodefact
     *
     * @return integer
     */
    public function getZipCodefact()
    {
        return $this->zipCodefact;
    }

    /**
     * Set townfact
     *
     * @param string $townfact
     *
     * @return Command
     */
    public function setTownfact($townfact)
    {
        $this->townfact = $townfact;

        return $this;
    }

    /**
     * Get townfact
     *
     * @return string
     */
    public function getTownfact()
    {
        return $this->townfact;
    }

    /**
     * Set addressdel
     *
     * @param string $addressdel
     *
     * @return Command
     */
    public function setAddressdel($addressdel)
    {
        $this->addressdel = $addressdel;

        return $this;
    }

    /**
     * Get addressdel
     *
     * @return string
     */
    public function getAddressdel()
    {
        return $this->addressdel;
    }

    /**
     * Set zipCodedel
     *
     * @param integer $zipCodedel
     *
     * @return Command
     */
    public function setZipCodedel($zipCodedel)
    {
        $this->zipCodedel = $zipCodedel;

        return $this;
    }

    /**
     * Get zipCodedel
     *
     * @return integer
     */
    public function getZipCodedel()
    {
        return $this->zipCodedel;
    }

    /**
     * Set towndel
     *
     * @param string $towndel
     *
     * @return Command
     */
    public function setTowndel($towndel)
    {
        $this->towndel = $towndel;

        return $this;
    }

    /**
     * Get towndel
     *
     * @return string
     */
    public function getTowndel()
    {
        return $this->towndel;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Command
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
     * Set mail
     *
     * @param string $mail
     *
     * @return Command
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set nb
     *
     * @param string $nb
     *
     * @return Command
     */
    public function setNb($nb)
    {
        $this->nb = $nb;

        return $this;
    }

    /**
     * Get nb
     *
     * @return string
     */
    public function getNb()
    {
        return $this->nb;
    }

    /**
     * Add work
     *
     * @param \WeCreaBundle\Entity\WorkPurchased $work
     *
     * @return Command
     */
    public function addWork(\WeCreaBundle\Entity\WorkPurchased $work)
    {
        $this->works[] = $work;

        return $this;
    }

    /**
     * Remove work
     *
     * @param \WeCreaBundle\Entity\WorkPurchased $work
     */
    public function removeWork(\WeCreaBundle\Entity\WorkPurchased $work)
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
