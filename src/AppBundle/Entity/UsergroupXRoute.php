<?php

namespace AppBundle\Entity;

/**
 * UsergroupXRoute
 */
class UsergroupXRoute
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Route
     */
    private $route;

    /**
     * @var \AppBundle\Entity\Usergroup
     */
    private $usergroup;


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
     * Set route
     *
     * @param \AppBundle\Entity\Route $route
     *
     * @return UsergroupXRoute
     */
    public function setRoute(\AppBundle\Entity\Route $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \AppBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set usergroup
     *
     * @param \AppBundle\Entity\Usergroup $usergroup
     *
     * @return UsergroupXRoute
     */
    public function setUsergroup(\AppBundle\Entity\Usergroup $usergroup = null)
    {
        $this->usergroup = $usergroup;

        return $this;
    }

    /**
     * Get usergroup
     *
     * @return \AppBundle\Entity\Usergroup
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }
}

