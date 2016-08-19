<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * SiteXSite
 */
class SiteXSite
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Site
     */
    private $childsite;

    /**
     * @var \AppBundle\Entity\Site
     */
    private $mainsite;


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
     * Set childsite
     *
     * @param \AppBundle\Entity\Site $childsite
     *
     * @return SiteXSite
     */
    public function setChildsite(\AppBundle\Entity\Site $childsite = null)
    {
        $this->childsite = $childsite;

        return $this;
    }

    /**
     * Get childsite
     *
     * @return \AppBundle\Entity\Site
     */
    public function getChildsite()
    {
        return $this->childsite;
    }

    /**
     * Set mainsite
     *
     * @param \AppBundle\Entity\Site $mainsite
     *
     * @return SiteXSite
     */
    public function setMainsite(\AppBundle\Entity\Site $mainsite = null)
    {
        $this->mainsite = $mainsite;

        return $this;
    }

    /**
     * Get mainsite
     *
     * @return \AppBundle\Entity\Site
     */
    public function getMainsite()
    {
        return $this->mainsite;
    }
    
    public function __construct()
    {
        $this->mainsite = new ArrayCollection();
        $this->childsite = new ArrayCollection();
    }
}

