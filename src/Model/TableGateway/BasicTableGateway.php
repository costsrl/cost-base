<?php
namespace CostBase\Model\TableGateway;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\Sql\Expression;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Adapter\Iterator;
use Laminas\Paginator\Adapter\ArrayAdapter;
use Laminas\Paginator\Paginator;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\Feature\GlobalAdapterFeature;
use Laminas\Db\TableGateway\Feature\EventFeature;
use Laminas\Db\TableGateway\Feature\FeatureSet;
use Laminas\ServiceManager\ServiceManager;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Db\TableGateway\Feature\MasterSlaveFeature;

abstract class BasicTableGateway extends AbstractTableGateway
{

    protected $isCacheEnabled = false;

    protected $cache;

    protected $inputFilter;

    protected $serviceLocator;

    protected $moduleOptionsTable;

    protected $eventManager;

    /**
     * @return mixed
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param mixed $eventManager
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
    }


    /**
     *
     * @return the $isCacheEnabled
     */
    public function getIsCacheEnabled()
    {
        return $this->isCacheEnabled;
    }

    /**
     *
     * @param field_type $isCacheEnabled
     */
    public function setIsCacheEnabled($isCacheEnabled)
    {
        $this->isCacheEnabled = $isCacheEnabled;
    }

    /**
     *
     * @return the $serviceLocator
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     *
     * @param field_type $serviceLocator
     */
    public function setServiceLocator(\Laminas\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     *
     * @return object Laminas\Cache\Storage\Adapter\AbstractAdapter
     */
    protected function getCacheService()
    {
        return $this->cache;
    }

    /**
     * set cache system
     * @param Laminas\Cache\Storage\Adapter\AbstractAdapter $cacheAdapter
     */
    protected function setCacheService(Laminas\Cache\Storage\Adapter\AbstractAdapter $cacheAdapter)
    {
        $this->cache = $cacheAdapter;
        return $this;
    }

    /**
     * method to implement to implemets
     */
    abstract protected function generateKey();

    public function __construct($table = 'nvgTable')
    {
        $this->table = $table;
    }

    /**
     *
     * @param CostBase\Options\ModuleOptions $options
     */
    public function initFeature($options){
        $this->featureSet = new FeatureSet();

        if($options->getTableGatewayGlobalAdapter())
            $this->featureSet->addFeature(new GlobalAdapterFeature());

        if($options->getTableGatewayEventFeauture()){
            $eventManager = ($this->getEventManager()) ?  : null;
            $this->featureSet->addFeature(new EventFeature($eventManager));
        }


        $this->initialize();
    }


    /**
     *
     * @param \Laminas\Db\ResultSet\ResultSet $resulset
     */
    public function setResultSetPrototype(\Laminas\Db\ResultSet\AbstractResultSet $resulset){
        $this->resultSetPrototype = $resulset;
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param AdapterInterface $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return mixed
     */
    public function getModuleOptionsTable()
    {
        return $this->moduleOptionsTable;
    }

    /**
     * @param mixed $moduleOptionsTable
     */
    public function setModuleOptionsTable($moduleOptionsTable)
    {
        $this->moduleOptionsTable = $moduleOptionsTable;
    }


    public function getProxitable($key){
        $aModuleOptionsTable = $this->getModuleOptionsTable();
        if(isset($aModuleOptionsTable[$key])){
            return $aModuleOptionsTable[$key];
        }
        else{
            return $key;
        }

    }

}
?>