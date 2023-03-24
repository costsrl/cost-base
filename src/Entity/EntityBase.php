<?php
namespace CostBase\Entity;

class EntityBase
{
    protected $serviceLocator;

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator($serviceManager)
    {
        $this->serviceLocator = $serviceManager;
        return $this;
    }
    
    /**
     *
     * @param string $property
     * @param array $calledMethods
     * @param array $calledSubProperty
     * @throws InvalidArgumentException
     * @return \CostBaseEnergy\Entity\EntityBase
     */
    public function orderCollection($property, $calledMethods = array(), $calledSubProperty = array())
    {
        /** @var Collection $collection */
        $collection = $this->$property;
        // If we have a PersistentCollection, make sure it is initialized, then unwrap it so we
        // can edit the underlying ArrayCollection without firing the changed method on the
        // PersistentCollection. We're only going in and changing the order of the underlying ArrayCollection.
        if ($collection instanceOf PersistentCollection) {
            /** @var PersistentCollection $collection */
            if (false === $collection->isInitialized()) {
                $collection->initialize();
            }
            $collection = $collection->unwrap();
        }
    
         
        if (!$collection instanceOf \Doctrine\Common\Collections\ArrayCollection) {
            throw new InvalidArgumentException('First argument of orderCollection must reference a PersistentCollection|ArrayCollection within $this.');
        }
    
    
    
        $uaSortFunction = function($first, $second) use ($calledMethods, $calledSubProperty) {
            // Loop through $calledMethods until we find a orderable difference
            foreach ($calledMethods as $callMethod => $order) {
    
                // If no order was set, swap k => v values and set ASC as default.
                if (false == in_array($order, array('ASC', 'DESC')) ) {
                    $callMethod = $order;
                    $order = 'ASC';
                }
                 
                if(isset($calledSubProperty[$callMethod])){
    
                    $firstValue = $first->$callMethod()->$calledSubProperty[$callMethod]();
                    $secondValue = $second->$callMethod()->$calledSubProperty[$callMethod]();
                }
                else{
                    $firstValue =   $first->$callMethod();
                    $secondValue =  $second->$callMethod();
                }
    
                 
                if (true == is_string($firstValue)) {
                    // String Compare
                    $result = strcasecmp($firstValue, $secondValue);
                } else {
    
                    // Numeric Compare
                    $difference = ($firstValue - $secondValue);
                    // This will convert non-zero $results to 1 or -1 or zero values to 0
                    // i.e. -22/22 = -1; 0.4/0.4 = 1;
                    $result = (0 != $difference) ? $difference / abs($difference): 0;
                }
    
                // 'Reverse' result if DESC given
                if ('DESC' == $order) {
                    $result *= -1;
                }
    
                // If we have a result, return it, else continue looping
                if (0 !== (int) $result) {
                    return (int) $result;
                }
            }
    
            // No result, return 0
            return 0;
        };
    
    
        // Get the values for the ArrayCollection and sort it using the function
        $values = $collection->getValues();
        uasort($values, $uaSortFunction);
    
         
        // Clear the current collection values and reintroduce in new order.
        $collection->clear();
        foreach ($values as $key => $item) {
            $collection->set($key, $item);
        }
    
        return $this;
    }
 
}

?>