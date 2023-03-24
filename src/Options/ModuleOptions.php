<?php

namespace CostBase\Options;

use Laminas\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $tableGatewayEventFeauture = false;

    /**
     * @var string
     */
    protected $tableGatewayGlobalAdapter = true;

    /**
     * @var array
     */
    private $dbAdapterServiceManagerKey = array();

    /**
     * @return array
     */
    public function getDbAdapterServiceManagerKey(): array
    {
        return $this->dbAdapterServiceManagerKey;
    }

    /**
     * @param array $dbAdapterServiceManagerKey
     */
    public function setDbAdapterServiceManagerKey(array $dbAdapterServiceManagerKey)
    {
        $this->dbAdapterServiceManagerKey = $dbAdapterServiceManagerKey;
    }


    /**
     * @return bool
     */
    public function isTableGatewayEventFeauture(): bool
    {
        return $this->tableGatewayEventFeauture;
    }

    /**
     * @param bool $tableGatewayEventFeauture
     */
    public function setTableGatewayEventFeauture(bool $tableGatewayEventFeauture)
    {
        $this->tableGatewayEventFeauture = $tableGatewayEventFeauture;
    }


    public function getTableGatewayEventFeauture()
    {
        return $this->tableGatewayEventFeauture;
    }


    public function getTableGatewayGlobalAdapter()
    {
        return $this->tableGatewayGlobalAdapter;
    }

    public function setTableGatewayGlobalAdapter($tableGatewayGlobalAdapter)
    {
        $this->tableGatewayGlobalAdapter = $tableGatewayGlobalAdapter;
        return $this;
    }
 

   
}
