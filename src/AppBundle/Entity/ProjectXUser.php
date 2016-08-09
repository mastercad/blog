<?php

namespace AppBundle\Entity;

/**
 * ProjectXUser
 */
class ProjectXUser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Usergroupproject
     */
    private $usergroupproject;

    /**
     * @var \AppBundle\Entity\User
     */
    private $user;

    /**
     * @var \AppBundle\Entity\Project
     */
    private $project;


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
     * Set usergroupproject
     *
     * @param \AppBundle\Entity\Usergroupproject $usergroupproject
     *
     * @return ProjectXUser
     */
    public function setUsergroupproject(\AppBundle\Entity\Usergroupproject $usergroupproject = null)
    {
        $this->usergroupproject = $usergroupproject;

        return $this;
    }

    /**
     * Get usergroupproject
     *
     * @return \AppBundle\Entity\Usergroupproject
     */
    public function getUsergroupproject()
    {
        return $this->usergroupproject;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return ProjectXUser
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set project
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return ProjectXUser
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }
}

