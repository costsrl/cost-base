<?php
namespace CostBase\Service\Classes;

use Laminas\Log\Logger;
use Doctrine\DBAL\Logging\DebugStack;

class DoctrineSqlLogger extends DebugStack 
{
    protected $logger;
    protected $level = 'debug';
    
    /**
     * @return the $level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    
    public function stopQuery()
    {
        parent::stopQuery();
        $level = $this->getLevel();
       
    
        $q = $this->queries[$this->currentQuery];
        $message = "Executed Query:  " . print_r($q, true);
        $this->logger->$level($message);
    }
    
    //array('sql' => $sql, 'params' => $params, 'types' => $types, 'executionMS' => 0);
    public function startQuery($sql, array $params = null, array $types = null)
    {
        parent::startQuery($sql, $params , $types);
        $level = $this->getLevel();
    
        $q = $this->queries[$this->currentQuery];
        $message  = "Sql Query:  " . print_r($q['sql'], true)." \n";
        $message .= "Params :". print_r($q['params'], true)." \n";
        $this->logger->$level($message);
    }
}

