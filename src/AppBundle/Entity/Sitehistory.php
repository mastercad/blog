<?php

namespace AppBundle\Entity;

/**
 * Sitehistory
 */
class Sitehistory
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \DateTime
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\User
     */
    private $createuser;

    /**
     * @var \AppBundle\Entity\User
     */
    private $updateuser;

    /**
     * @var \AppBundle\Entity\Site
     */
    private $sites;


    /**
     * Set content
     *
     * @param string $content
     *
     * @return Sitehistory
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
     * @return Sitehistory
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Sitehistory
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Sitehistory
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
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
     * Set createuser
     *
     * @param \AppBundle\Entity\User $createuser
     *
     * @return Sitehistory
     */
    public function setCreateuser(\AppBundle\Entity\User $createuser = null)
    {
        $this->createuser = $createuser;

        return $this;
    }

    /**
     * Get createuser
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreateuser()
    {
        return $this->createuser;
    }

    /**
     * Set updateuser
     *
     * @param \AppBundle\Entity\User $updateuser
     *
     * @return Sitehistory
     */
    public function setUpdateuser(\AppBundle\Entity\User $updateuser = null)
    {
        $this->updateuser = $updateuser;

        return $this;
    }

    /**
     * Get updateuser
     *
     * @return \AppBundle\Entity\User
     */
    public function getUpdateuser()
    {
        return $this->updateuser;
    }

    /**
     * Set sites
     *
     * @param \AppBundle\Entity\Site $sites
     *
     * @return Sitehistory
     */
    public function setSites(\AppBundle\Entity\Site $sites = null)
    {
        $this->sites = $sites;

        return $this;
    }

    /**
     * Get sites
     *
     * @return \AppBundle\Entity\Site
     */
    public function getSites()
    {
        return $this->sites;
    }
}

