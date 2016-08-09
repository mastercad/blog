<?php

namespace AppBundle\Entity;

/**
 * Site
 */
class Site
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
     * @var boolean
     */
    private $visibility = '1';

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
    private $updateuser;

    /**
     * @var \AppBundle\Entity\User
     */
    private $createuser;


    /**
     * Set content
     *
     * @param string $content
     *
     * @return Site
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
     * @return Site
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
     * Set visibility
     *
     * @param boolean $visibility
     *
     * @return Site
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return boolean
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Site
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
     * @return Site
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
     * Set updateuser
     *
     * @param \AppBundle\Entity\User $updateuser
     *
     * @return Site
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
     * Set createuser
     *
     * @param \AppBundle\Entity\User $createuser
     *
     * @return Site
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
}

