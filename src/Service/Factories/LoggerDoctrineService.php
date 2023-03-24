<?php
namespace CostBase\Service\Factories;
use CostBase\Service\Classes\DoctrineSqlLogger as DoctrineSqlLogger;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Log\Logger;
use Laminas\Log\Writer;
use Laminas\Log\Filter;
use Laminas\Log\Formatter;
use Laminas\Log;

class LoggerDoctrineService implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // TODO: Auto-generated method stub
        $aParams        = $container->get('logger_doctrine_config');
        $sPathFilename  = $aParams['path_filename'];
        $sFileName      = $aParams['log_filename'];
        $sPriority      = $aParams['priority'];
        $oWriter        = new Writer\Stream($sPathFilename.DIRECTORY_SEPARATOR.$aParams['log_filename'].".txt","a");

        $oFormatter     = new Formatter\Simple('%timestamp% | %message%');
        $oFilter	    = new Filter\Priority((int) $sPriority);
        $oWriter->setFormatter($oFormatter);
        $oWriter->addFilter($oFilter);
        $oLogger = new Logger();
        $oLogger->addWriter($oWriter);
        $doctrineLogger = new DoctrineSqlLogger($oLogger,$container);
        return $doctrineLogger;

    }
    
}

