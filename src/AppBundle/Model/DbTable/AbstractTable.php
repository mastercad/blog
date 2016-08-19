<?php 
/**
 * @category
 * @package
 * @subpackage  Adapter
 * @copyright   Copyright (c) 2014 Unister TRAVEL Betriebsgesellschaft mbH
 * @author      Unister TRAVEL Betriebsgesellschaft mbH <teamleitung-dev@unister.de>
 * @author      Vorname Nachname <vorname.nachname@unister.de>
 * @version     $$Id:$$
 */
namespace AppBundle\Model\DbTable;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

abstract class AbstractTable
{
    /**
     * @var string
     */
    protected $_tableName;

    /**
     * @var \PDO
     */
    protected $_pdo = null;

    /**
     * var string
     */
    protected $_sConnectionSection;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $_container
     */
    protected $_oContainer = null;

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->_oContainer;
    }

    /**
     * @param Container $oContainer
     * @return $this
     */
    public function setContainer($oContainer)
    {
        $this->_oContainer = $oContainer;
        return $this;
    }

    public function __construct(Container $oContainer)
    {
        $this->_oContainer = $oContainer;
    }

    /**
     * @return string
     */
    protected function _getTableName()
    {
        return $this->_tableName;
    }

    /**
     * @param string $sTableName
     * @return $this
     */
    protected function _setTableName($sTableName)
    {
        $this->_tableName = $sTableName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConnectionSection()
    {
        return $this->_sConnectionSection;
    }

    /**
     * @param mixed $sConnectionSection
     * @return $this
     */
    public function setConnectionSection($sConnectionSection)
    {
        $this->_sConnectionSection = $sConnectionSection;
        return $this;
    }

    /**
     * @return \PDO
     */
    public function usePdo()
    {
        if( true === is_null($this->_pdo)) {
            $this->_pdo = $this->getContainer()->get($this->getConnectionSection())->useConnectorPDO();
        }
        return $this->_pdo;
    }
    
    public function query($sQueryString) 
    {
        return $this->usePDO()->query($sQueryString)->fetchAll(\PDO::FETCH_ASSOC);
    }
}
