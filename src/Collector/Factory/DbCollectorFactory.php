<?php
namespace CostBase\Collector\Factory;
use CostBase\Collector\DbCollector;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;


class DbCollectorFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return $this->createService($container);
    }

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $moduleOptions = $this->getModuleOptions($serviceLocator);
        $dbCollector = new DbCollector();
        
        foreach($moduleOptions->getDbAdapterServiceManagerKey() as $adapterServiceKey) {
            if($serviceLocator->has($adapterServiceKey)) {
                $profiler = $serviceLocator->get($adapterServiceKey)
                    ->getProfiler();

                if(null != $profiler) {
                    $dbCollector->addProfiler($adapterServiceKey, $profiler);
                }
            }
        }

        return $dbCollector;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ModuleOptions
     */
    private function getModuleOptions(ServiceLocatorInterface $serviceLocator) {
        return $serviceLocator->get('cost-base-options');
    }

}