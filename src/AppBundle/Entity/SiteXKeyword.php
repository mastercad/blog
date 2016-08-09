<?php

namespace AppBundle\Entity;

/**
 * SiteXKeyword
 */
class SiteXKeyword
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Keyword
     */
    private $keyword;

    /**
     * @var \AppBundle\Entity\Site
     */
    private $site;


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
     * Set keyword
     *
     * @param \AppBundle\Entity\Keyword $keyword
     *
     * @return SiteXKeyword
     */
    public function setKeyword(\AppBundle\Entity\Keyword $keyword = null)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return \AppBundle\Entity\Keyword
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set site
     *
     * @param \AppBundle\Entity\Site $site
     *
     * @return SiteXKeyword
     */
    public function setSite(\AppBundle\Entity\Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return \AppBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }
}

