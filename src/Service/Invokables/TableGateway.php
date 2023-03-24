<?php
namespace CostBase\Service\Invokables;

use Laminas\EventManager\EventManagerInterface;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Db\TableGateway\TableGateway as DbTableGateway;
use Laminas\Db\ResultSet\ResultSet;

class TableGateway 
{

    /**
     *
     * @var array
     */
    protected static $cache;

    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    public function get($tableName, $features = null, $resultSetPrototype = null)
    {
       
        $sCacheKey = $tableName;
        // $cacheKey = md5(serialize($tableName.$features.$resultSetPrototype));
        if (@isset(self::$cache[$key])) {
            return self::$cache;
        }        
        
        $aConfig = $this->getServiceLocator()->get('config');
        // defined which class should be used for which table
        $aTableGatewayMap = $aConfig['table-gateway']['map'];
        if (isset($aTableGatewayMap[$tableName])) {
            $options = $this->getServiceLocator()->get('cost-base-options');
            $className = $aTableGatewayMap[$tableName];                            
            $oTblObject = new $className($tableName);
            if(method_exists($oTblObject,'setEventManager')){
                if($this->getServiceLocator()->has(EventManagerInterface::class)){
                    $oTblObject->setEventManager($this->getServiceLocator()->get(EventManagerInterface::class));
                }
            }
            $oTblObject->initFeature($options);
            self::$cache[$sCacheKey] = $oTblObject;
            if($resultSetPrototype !== null){
                self::$cache[$sCacheKey]->setResultSetPrototype($resultSetPrototype);
                self::$cache[$sCacheKey]->initialize();
            }
            
        } else {
            $db = $this->getServiceLocator()->get('Laminas\Db\Adapter\Adapter');
            self::$cache[$sCacheKey] = new DbTableGateway($tableName, $db, $features, $resultSetPrototype);
        }
        
        return self::$cache[$sCacheKey];
    }

    /*
     * (non-PHPdoc)
     * @see \Laminas\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /*
     * (non-PHPdoc)
     * @see \Laminas\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
