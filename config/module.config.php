<?php
namespace CostBase;
use CostBase\Collector\Factory\DbCollectorFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return array(
    'service_manager' => array(
        'factories' => array(
            'session_admin_manager'     => 'CostBase\Service\Factories\SessionService',
            'logger_admin_manager'      => 'CostBase\Service\Factories\LoggerService',
            'doctrine_logger_sql'       => 'CostBase\Service\Factories\LoggerDoctrineService',
            'text-cache'                => 'Laminas\Cache\Service\StorageCacheFactory',
            'var-cache'                 => 'CostBase\Service\Factories\VariableCache',
            'table-gateway'             => 'CostBase\Service\Factories\TableGatewayFactory',
            'cost-base-options'          => function ($sm) {
                                           $config = $sm->get('Config');
                                           return new Options\ModuleOptions(isset($config['cost-base']) ? $config['cost-base'] : array());
            },
            'CostBase\Collector\DbCollector' => DbCollectorFactory::class,
        ),
        'invokables' => array(
            //'table-gateway'             => 'CostBase\Service\Invokables\TableGateway'
        ),
        'services' => array(
            'Cost_session_namespace' => 'Costnamespace',
            'logger_admin_config' => array(
                'path_filename' => realpath(dirname(__FILE__) . '/../../../../data/logs'),
                'log_filename' => 'LOGGER_' . date('ymd', time()),
                'priority' => '7'
            ),
            'logger_doctrine_config' => array(
                'path_filename' => realpath(dirname(__FILE__) . '/../../../../data/logs'),
                'log_filename' => 'LOGGER_DOCTRINE_' . date('ymd', time()),
                'priority' => '7'
            ),
        )
    ),
    'router'=>array(
        'ruotes'=>array(
            'error' => array(
                'type' => 'Laminas\Mvc\Router\Http\Literal',
                'priority' => 1001,
                'options' => array(
                    'route' => '/error',
                    'defaults' => array(
                        'controller' => 'CostBase\Controller\Index',
                        'template'   => 'error/error',
                        'layout'     => 'layout/static',
                        'action'     => 'error',
                        'section'    => 'static'
                    ),
                ),
            ),
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'CostBase\Controller\Index' => InvokableFactory::class
         ),
    ),
    'view_manager' => array(
        'template_map' => array(
            /*'layout/static'           => __DIR__ . '/../view/layout/static.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'partial/header'          => __DIR__ . '/../view/partial/header.phtml',
            'partial/footer'          => __DIR__ . '/../view/partial/footer.phtml',
            */
        ),
        
        'template_path_stack' => array(
            __DIR__ . '/../view',
            __DIR__ . '/../view/Cost-base/partial',
        ),
    ),
    'laminasdevelopertools' => array(
        'profiler' => array(
            'collectors' => array(
                'CostBaseDbProfiler' => 'CostBase\Collector\DbCollector',
            ),
        ),
        'toolbar' => array(
            'entries' => array(
                'CostBaseDbProfiler' => 'toolbar/db',
            ),
        ),
    ),
    'costbase_db_profiler' => array(
        'dbAdapterServiceManagerKey' => 'Laminas\Db\Adapter\Adapter',
    ),
);