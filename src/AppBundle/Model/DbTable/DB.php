<?php
/**
 * Kurze Beschreibung der Datei
 *
 * Lange Beschreibung der Datei (wenn vorhanden)...
 *
 * @package    UNKNOWN
 * @copyright  Copyright (c) 2016 Unister GmbH
 * @author     Unister GmbH <entwicklung@unister.de>
 * @author     Andreas Kempe <andreas.kempe@unister.de>
 * @version    $Id$
 */

/**
 * Kurze Beschreibung der Klasse
 *
 * Lange Beschreibung der Klasse (wenn vorhanden)...
 *
 * @category
 * @package
 * @subpackage  Adapter
 * @copyright   Copyright (c) 2014 Unister TRAVEL Betriebsgesellschaft mbH
 */

namespace AppBundle\Model\DbTable;

use Symfony\Component\Config\Definition\Exception\InvalidDefinitionException;

class DB
{
    /**
     * @var string
     */
    protected $_sDBMS = null;

    /**
     * @var string
     */
    protected $_sDns;

    /**
     * @var string
     */
    protected $_sHost;
    /**
     * @var string
     */
    protected $_sUser;
    /**
     * @var string
     */
    protected $_sPassword;
    /**
     * @var string
     */
    protected $_sPort;

    /**
     * @var \PDO
     */
    protected $_oPdoConnector = null;

    /**
     * @var string
     */
    protected $_sDatabaseName;

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->_sHost;
    }

    /**
     * @param string $sHost
     * 
     * @return $this
     */
    public function setHost($sHost)
    {
        $this->_sHost = $sHost;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_sPassword;
    }

    /**
     * @param string $sPassword
     * 
     * @return $this
     */
    public function setPassword($sPassword)
    {
        $this->_sPassword = $sPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->_sPort;
    }

    /**
     * @param string $sPort
     * 
     * @return $this
     */
    public function setPort($sPort=null)
    {
        if (true === is_null($sPort) ) {
            $sPort = 3306;
        }
        $this->_sPort = $sPort;
        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->_sUser;
    }

    /**
     * @param string $sUser
     * 
     * @return $this
     */
    public function setUser($sUser)
    {
        $this->_sUser = $sUser;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->_sDatabaseName;
    }

    /**
     * @return string
     */
    public function getDns()
    {
        return $this->_sDns;
    }

    /**
     * @param string $sDns
     * 
     * @return $this
     */
    public function setDns($sDns)
    {
        $this->_sDns = $sDns;
        return $this;
    }

    /**
     * @return string
     */
    public function getDBMS()
    {
        return $this->_sDBMS;
    }

    /**
     * @param string $sDBMS
     * 
     * @return $this
     */
    public function setDBMS($sDBMS)
    {
        $this->_sDBMS = $sDBMS;
        return $this;
    }

    private function _generateDns()
    {
        if (false === empty($this->_sDBMS)) {
            $this->setDns($this->getDBMS() . ':');
        } else {
            throw new InvalidDefinitionException('DBMS is missing in config');
        }
        if (false === empty($this->_sHost)) {
            $this->setDns($this->getDns() . 'host=' . $this->getHost());
        } else {
            throw new InvalidDefinitionException('Host is missing in config');
        }
        if (false === empty($this->_sPort)) {
            $this->setDns($this->getDns() . ';port=' . $this->getPort());
        }
        if (false === empty($this->_sDatabaseName)) {
            $this->setDns($this->getDns() . ';dbname=' . $this->getDatabaseName());
        }
        return $this;
    }

    /**
     * @param string $sDatabaseName
     * 
     * @return $this
     */
    public function setDatabaseName($sDatabaseName)
    {
        $this->_sDatabaseName = $sDatabaseName;
        return $this;
    }

    /**
     * @return \PDO
     */
    public function useConnectorPDO()
    {
        if (true === is_null($this->_oPdoConnector)) {
            $this->_generateDns();
            $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
            $this->_oPdoConnector = new \PDO($this->getDns(), $this->getUser(), $this->getPassword(), $options);
        }
        return $this->_oPdoConnector;
    }
}