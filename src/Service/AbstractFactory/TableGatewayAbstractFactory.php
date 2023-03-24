<?php

namespace CostBase\Service\AbstractFactory;

use Movies\Entities\Movie;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\ArraySerializable;
use Laminas\Hydrator\ClassMethodsHydrator as ClassMethods;
use Laminas\Hydrator\NamingStrategy\MapNamingStrategy;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

class TableGatewayAbstractFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (fnmatch('*TableGateway', $requestedName)) {
            return true;
        }

        return false;
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return null|object|TableGateway
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(Adapter::class);
        $tableGateway = null;
        $tableName    = substr($requestedName,0,strpos($requestedName,'TableGateway')-1);

        $tableGateway = new TableGateway(
            $tableName,
            $dbAdapter,
            null,
            null
        );

        return $tableGateway;
    }
}
