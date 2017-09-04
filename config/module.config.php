<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine;

use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'router' => [
        'routes' => [
            'cpanel' => [
                'options' => [
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'layout' => [
                        'options' => [
                            'defaults' => [
                                'controller' => Controller\LayoutController::class,
                            ],
                        ],
                    ],
                    'module' => [
                        'options' => [
                            'defaults' => [
                                'controller' => Controller\ModuleController::class,
                            ],
                        ],
                    ],
                    'page-type' => [
                        'options' => [
                            'defaults' => [
                                'controller' => Controller\PageTypeController::class,
                            ],
                        ],
                    ],
                    'route' => [
                        'options' => [
                            'defaults' => [
                                'controller' => Controller\RouteController::class,
                            ],
                        ],
                    ],
                    'setting' => [
                        'options' => [
                            'defaults' => [
                                'controller' => Controller\SettingController::class,
                            ],
                        ],
                    ],
                    'theme' => [
                        'options' => [
                            'defaults' => [
                                'controller' => Controller\ThemeController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\LayoutController::class => InvokableFactory::class,
            Controller\ModuleController::class => InvokableFactory::class,
            Controller\PageTypeController::class => InvokableFactory::class,
            Controller\RouteController::class => InvokableFactory::class,
            Controller\SettingController::class => InvokableFactory::class,
            Controller\ThemeController::class => InvokableFactory::class,
        ],
        'initializers' => [
            new Initializer\LazyControllerInitializer
        ],
    ],

    'form_elements' => [
        'aliases' => [
            Controller\LayoutController::class =>
                \MSBios\Resource\Form\LayoutForm::class,
            Controller\ModuleController::class =>
                \MSBios\Resource\Form\ModuleForm::class,
            Controller\PageTypeController::class =>
                \MSBios\Resource\Form\ModuleForm::class,
        ],
    ],

    'service_manager' => [
        'abstract_factories' => [],
        'factories' => [],
    ],

    \MSBios\Theme\Module::class => [

        'themes' => [
            'limitless' => [
                // Template Map
                'template_map' => [
                ],
                // Template Path Stack
                'template_path_stack' => [
                    __DIR__ . '/../themes/limitless/view/',
                ],
                // Controller map
                'controller_map' => [
                ],
                // Translation file patterns
                'translation_file_patterns' => [
                    [
                        'type' => 'gettext',
                        'base_dir' => __DIR__ . '/../themes/limitless/language/',
                        'pattern' => '%s.mo',
                    ],
                ],
                // Widget manager
                'widget_manager' => [
                    'template_map' => [
                    ],
                    'template_path_stack' => [
                        __DIR__ . '/../themes/limitless/widget/'
                    ],
                ],
            ],
        ]
    ]
];
