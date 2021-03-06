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
        'MSBios\Session',
        'MSBios\Permissions\Acl',
        'MSBios\Guard\DeveloperTools',
        'MSBios\Paginator\Doctrine',
        'Zend\Log',
        'MSBios\Validator',
        'MSBios\Portal',
        'MSBios\Application',
        'MSBios\Theme',
        'MSBios\Widget',
        'MSBios\Assetic',
        'MSBios\Cache',
        'Zend\Serializer',
        'MSBios\I18n',
        'Zend\I18n',
        'MSBios\Navigation',
        'Zend\Navigation',
        'MSBios\CPanel',
        'MSBios\Hydrator',
        'MSBios\View',
        'Zend\Mvc\Plugin\FilePrg',
        'Zend\Mvc\Plugin\Identity',
        'Zend\Mvc\Plugin\Prg',
        'Zend\Mvc\Plugin\FlashMessenger',
        'MSBios\Guard\CPanel',
        'MSBios\Db',
        'Zend\Db',
        'Zend\Session',
        'Zend\Validator',
        'MSBios\Guard\Doctrine',
        'Zend\Cache',
        'Zend\Paginator',
        'Zend\Filter',
        'Zend\Form',
        'Zend\Router',
        'Zend\InputFilter',
        'Zend\Hydrator',
        'DoctrineModule',
        'DoctrineORMModule',
        'MSBios\Form',
        'MSBios\Guard',
        'MSBios\Resource',
        'MSBios\Authentication',
        'MSBios\Guard\Resource',
        'MSBios\Doctrine',
        'MSBios\Form\Doctrine',
        'MSBios\CPanel\Doctrine',
        'MSBios\Resource\Doctrine',
        'MSBios\Authentication\Doctrine',
        'MSBios\Guard\Resource\Doctrine',
        'MSBios\Guard\CPanel\Doctrine',
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
