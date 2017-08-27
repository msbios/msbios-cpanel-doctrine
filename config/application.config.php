<?php
/**
 * If you need an environment-specific system or application configuration,
 * there is an example in the documentation
 * @see https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-system-configuration
 * @see https://docs.zendframework.com/tutorials/advanced-config/#environment-specific-application-configuration
 */
return [
    // Retrieve list of modules used in this application.
    'modules' => [

        'Zend\Form',

        'Zend\Mvc\Plugin\FlashMessenger',
        'Zend\I18n',
        'Zend\Navigation',
        'Zend\Router',
        'Zend\InputFilter',
        'Zend\Hydrator',

        'DoctrineModule',
        'DoctrineORMModule',

        'MSBios\Assetic',
        'MSBios\Widget',
        'MSBios\Theme',
        'MSBios\Navigation',
        'MSBios\Application',
        'MSBios\Resource',
        'MSBios\Authentication',
        'MSBios\Guard',
        'MSBios\CPanel',
        'MSBios\Guard\CPanel',
        'MSBios\Guard\Resource',

        'Zend\Log',
        'ZendDeveloperTools',
    ],

    'module_listener_options' => [
        'module_paths' => [
            './module',
            './vendor',
        ],
        'config_glob_paths' => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
        'config_cache_enabled' => false,
        // 'config_cache_key' => 'application.config.cache',
        'module_map_cache_enabled' => false,
        // 'module_map_cache_key' => 'application.module.cache',
        'cache_dir' => 'data/cache/',
    ],
];
