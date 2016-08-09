<?php

namespace AppBundle\Entity;

/**
 * Usergroupproject
 */
class Usergroupproject
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $read;

    /**
     * @var boolean
     */
    private $create;

    /**
     * @var boolean
     */
    private $edit;

    /**
     * @var boolean
     */
    private $delete;

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
     * Set name
     *
     * @param string $name
     *
     * @return Usergroupproject
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
     * Set read
     *
     * @param boolean $read
     *
     * @return Usergroupproject
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Set create
     *
     * @param boolean $create
     *
     * @return Usergroupproject
     */
    public function setCreate($create)
    {
        $this->create = $create;

        return $this;
    }

    /**
     * Get create
     *
     * @return boolean
     */
    public function getCreate()
    {
        return $this->create;
    }

    /**
     * Set edit
     *
     * @param boolean $edit
     *
     * @return Usergroupproject
     */
    public function setEdit($edit)
    {
        $this->edit = $edit;

        return $this;
    }

    /**
     * Get edit
     *
     * @return boolean
     */
    public function getEdit()
    {
        return $this->edit;
    }

    /**
     * Set delete
     *
     * @param boolean $delete
     *
     * @return Usergroupproject
     */
    public function setDelete($delete)
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * Get delete
     *
     * @return boolean
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Usergroupproject
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
     * @return Usergroupproject
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
}

