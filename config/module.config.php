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
            Controller\ModuleController::class => \MSBios\CPanel\Factory\LazyActionControllerFactory::class,
            Controller\PageTypeController::class => \MSBios\CPanel\Factory\LazyActionControllerFactory::class,
            Controller\RouteController::class => \MSBios\CPanel\Factory\LazyActionControllerFactory::class,
            Controller\SettingController::class => \MSBios\CPanel\Factory\LazyActionControllerFactory::class,
            Controller\ThemeController::class => \MSBios\CPanel\Factory\LazyActionControllerFactory::class,
        ],
        'initializers' => [
            new Initializer\LazyControllerInitializer
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
    ],

    \MSBios\CPanel\Module::class => [
        'controllers' => [ // key controller
            Controller\LayoutController::class => [
                'object_class' => \MSBios\Resource\Doctrine\Entity\Layout::class,
                'form_element' => \MSBios\Resource\Form\LayoutForm::class,
                'item_count_per_page' => 10
            ],
            Controller\ModuleController::class => [
                'resource' => \MSBios\CPanel\Controller\ModuleController::class,
                'object_class' => \MSBios\Resource\Entity\Module::class,
                // 'form_element' => \MSBios\Resource\Form\ModuleForm::class
            ],
            Controller\PageTypeController::class => [
                'resource' => \MSBios\CPanel\Controller\PageTypeController::class,
                'object_class' => \MSBios\Resource\Entity\PageType::class,
                // 'form_element' => \MSBios\Resource\Form\UserForm::class
            ],
            Controller\RouteController::class => [
                'resource' => \MSBios\CPanel\Controller\RouteController::class,
                'object_class' => \MSBios\Resource\Entity\PageType::class,
                // 'form_element' => \MSBios\Resource\Form\UserForm::class
            ],
            Controller\SettingController::class => [
                'resource' => \MSBios\CPanel\Controller\SettingController::class,
                'object_class' => \MSBios\Resource\Entity\Setting::class,
                // 'form_element' => \MSBios\Resource\Form\UserForm::class
            ],
            Controller\ThemeController::class => [
                'resource' => \MSBios\CPanel\Controller\ThemeController::class,
                'object_class' => \MSBios\Resource\Entity\Theme::class,
                // 'form_element' => \MSBios\Resource\Form\ThemeForm::class
            ]
        ]
    ],
];
