<?php
namespace CostBase;

use Laminas\Mvc\MvcEvent;
use Laminas\Console\Console;
use Laminas\Console\Request as ConsoleRequest;


class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    

    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__
                )
            )
        );
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager       = $e->getApplication()->getEventManager();
        $serviceManager     = $e->getApplication()->getServiceManager();
        $shareEventManager  = $eventManager->getSharedManager(); // The shared event manager
        
        $adapter = $serviceManager->get('Laminas\Db\Adapter\Adapter');
        \Laminas\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($adapter);
 
        /**  --commentati di default--
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleDispatchError'), 100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleGeneralExceptions'), 9);
        **/
        
        if($serviceManager->has('doctrine.entitymanager.orm_default')){
            $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
            $entityManager->getEventManager()->addEventListener(array(\Doctrine\ORM\Events::postLoad), new \CostBase\Listeners\EntityInjectorListener($serviceManager));
        
        }
        
        
        if($serviceManager->has('doctrine.entitymanager.orm_default')){
            $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');
            $entityManager->getEventManager()->addEventListener(array(\Doctrine\ORM\Events::prePersist), new \CostBase\Listeners\EntityInjectorListener($serviceManager));
        
        }
        
        
    }
    
    
    /**
     * @param MvcEvent $e
     * To do gestire un eventuale logger sulla gestione dell'eccezione
     */
    public function handleDispatchError(MvcEvent $e)
    {
        $request = $e->getRequest();
        if($this->isConsoleRequest($request)) {
            return;
        }
    
        $statusCode = $e->getResponse()->getStatusCode();
        //echo $statusCode; die; //200
    
        $sm = $e->getApplication()->getServiceManager();
        /** @var Logger $logger */
        $logger = $sm->get('CostLogger');
    
        if($e->getParam('exception')) {
            $logger->crit($e->getParam('exception'));
        }
    
        if($e->getParam('error')) {
            $logger->crit($e->getParam('error'));
        }
    
        $exception = $e->getParam('exception');
    
        if ($exception && $exception instanceof \Exception) {
            $e->getApplication()->getEventManager()->trigger('superadmin.errorlog', $this, array(
                'errore' => $exception
            ));
        }
    }
    
    
    /**
     * Ritorna true se la richiesta proviene dalla console
     *
     * @param $request
     * @return bool
     */
    private function isConsoleRequest($request)
    {
        return ($request instanceof ConsoleRequest);
    }
    
    
    /**
     * Gestisce le eccezioni del sistema.
     *
     * @param Event $e
     * redirect pagina di errore personalizzata
     */
    public function handleGeneralExceptions(MvcEvent $e)
    {
        $exception = $e->getParam('exception');
        if ($exception && $exception instanceof \Exception) {
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', '/error');
            $response->setStatusCode(302);
            $e->stopPropagation(true);
    
            return $response;
        }
    }
}





