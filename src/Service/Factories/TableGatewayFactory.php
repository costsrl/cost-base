<?php
namespace CostBase\Service\Factories;

//use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use CostBase\Service\Invokables\TableGateway;
use Interop\Container\ContainerInterface;

class TableGatewayFactory implements FactoryInterface
{
   
    /*
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Translator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $oTableGateway = new TableGateway();
        $oTableGateway->setServiceLocator($container);
        return $oTableGateway;
    }
    
}

?>