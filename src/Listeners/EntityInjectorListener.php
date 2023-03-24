<?php
namespace CostBase\Listeners;

class EntityInjectorListener
{
    protected $serviceLocator;
    
    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    /*
     * postLoad injection
     */ 
    public function postLoad($eventArgs)
    {
        // check if entity is a child of abstract class
        if ($eventArgs->getEntity() instanceof \CostBase\Entity\EntityBase) 
                $eventArgs->getEntity()->setServiceLocator($this->serviceLocator);
        
    }
    
    /*
     * prePersit injection
     */ 
    public function prePersist($eventArgs)
    {
        // check if entity is a child of abstract class
        if ($eventArgs->getEntity() instanceof \CostBase\Entity\EntityBase)
            $eventArgs->getEntity()->setServiceLocator($this->serviceLocator);
    
    }
}

?>