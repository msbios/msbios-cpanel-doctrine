<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine;

return [

    'router' => [
        'routes' => [
            'cpanel' => [
                // 'options' => [
                //     'defaults' => [
                //         'controller' => Controller\IndexController::class,
                //     ]
                // ],
                'may_terminate' => true,
                'child_routes' => [
                    'layout' => [
                        'options' => [
                            'defaults' => [
                                'controller' => Controller\LayoutController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    //'module' => [
                    //    'type' => Segment::class,
                    //    'options' => [
                    //        'route' => 'module[/[:action[/[:id[/]]]]]',
                    //        'defaults' => [
                    //            'controller' => Controller\ModuleController::class,
                    //        ],
                    //        'constraints' => [
                    //            'action' => 'add|edit|drop',
                    //            'id' => '[0-9]+'
                    //        ]
                    //    ]
                    //],
                    //'page-type' => [
                    //    'type' => Segment::class,
                    //    'options' => [
                    //        'route' => 'page-type[/[:action[/[:id[/]]]]]',
                    //        'defaults' => [
                    //            'controller' => Controller\PageTypeController::class,
                    //        ],
                    //        'constraints' => [
                    //            'action' => 'add|edit|drop',
                    //            'id' => '[0-9]+'
                    //        ]
                    //    ]
                    //],
                    //'route' => [
                    //    'type' => Segment::class,
                    //    'options' => [
                    //        'route' => 'route[/[:action[/[:id[/]]]]]',
                    //        'defaults' => [
                    //            'controller' => Controller\RouteController::class,
                    //        ],
                    //        'constraints' => [
                    //            'action' => 'add|edit|drop',
                    //            'id' => '[0-9]+'
                    //        ]
                    //    ]
                    //],
                    //'setting' => [
                    //    'type' => Segment::class,
                    //    'options' => [
                    //        'route' => 'setting[/[:action[/[:id[/]]]]]',
                    //        'defaults' => [
                    //            'controller' => Controller\SettingController::class,
                    //        ],
                    //        'constraints' => [
                    //            'action' => 'add|edit|drop',
                    //            'id' => '[0-9]+'
                    //        ],
                    //    ]
                    //],
                    //'theme' => [
                    //    'type' => Segment::class,
                    //    'options' => [
                    //        'route' => 'theme[/[:action[/[:id[/]]]]]',
                    //        'defaults' => [
                    //            'controller' => Controller\ThemeController::class,
                    //        ],
                    //        'constraints' => [
                    //            'action' => 'add|edit|drop',
                    //            'id' => '[0-9]+'
                    //        ],
                    //    ],
                    //],
                ],
            ],
        ],
    ],

    'controllers' => [

        'factories' => [
            // Controller\IndexController::class => Factory\LazyActionControllerFactory::class,
            Controller\LayoutController::class => \MSBios\CPanel\Factory\LazyActionControllerFactory::class,
            //Controller\ModuleController::class => Factory\LazyActionControllerFactory::class,
            //Controller\PageTypeController::class => Factory\LazyActionControllerFactory::class,
            //Controller\RouteController::class => Factory\LazyActionControllerFactory::class,
            //Controller\SettingController::class => Factory\LazyActionControllerFactory::class,
            //Controller\ThemeController::class => Factory\LazyActionControllerFactory::class,
        ]
    ],

    //'view_manager' => [
    //    'template_map' => [
    //        'ms-bios/c-panel/doctrine/layout/index' => 'ms-bios/c-panel/layout/index'
    //    ],
    //    'template_path_stack' => [
    //        __DIR__ . '/../view',
    //    ],
    //],

    \MSBios\Theme\Module::class => [

        'themes' => [
            'limitless' => [
                'template_map' => [
                    'ms-bios/c-panel/doctrine/layout/index' =>
                        './vendor/msbios/cpanel/themes/limitless/view/ms-bios/c-panel/layout/index.phtml',
                    'ms-bios/c-panel/doctrine/layout/edit' =>
                        './vendor/msbios/cpanel/themes/limitless/view/ms-bios/c-panel/layout/edit.phtml',
                ],
            ],
        ]
    ],

    \MSBios\CPanel\Module::class => [
        'controllers' => [ // key controller
            Controller\LayoutController::class => [
                'resource' => \MSBios\CPanel\Controller\LayoutController::class,
                // 'route_name' => 'cpanel/layout',
                'resource_class' => \MSBios\Resource\Entity\Layout::class,
                'form_element' => \MSBios\Resource\Form\LayoutForm::class
            ],
            // Controller\ModuleController::class => [
            //     'resource' => Controller\ModuleController::class,
            //     'route_name' => 'cpanel/module',
            //     'resource_class' => \MSBios\Resource\Entity\Module::class,
            //     'form_element' => \MSBios\Resource\Form\ModuleForm::class
            // ],
            // Controller\PageTypeController::class => [
            //     'resource' => Controller\PageTypeController::class,
            //     'route_name' => 'cpanel/page-type',
            //     'resource_class' => \MSBios\Resource\Entity\PageType::class,
            //     // 'form_element' => \MSBios\Resource\Form\UserForm::class
            // ],
            // Controller\RouteController::class => [
            //     'resource' => Controller\RouteController::class,
            //     'route_name' => 'cpanel/route',
            //     'resource_class' => \MSBios\Resource\Entity\PageType::class,
            //     // 'form_element' => \MSBios\Resource\Form\UserForm::class
            // ],
            // Controller\SettingController::class => [
            //     'resource' => Controller\SettingController::class,
            //     'route_name' => 'cpanel/setting',
            //     'resource_class' => \MSBios\Resource\Entity\Setting::class,
            //     // 'form_element' => \MSBios\Resource\Form\UserForm::class
            // ],
            // Controller\ThemeController::class => [
            //     'resource' => Controller\ThemeController::class,
            //     'route_name' => 'cpanel/theme',
            //     'resource_class' => \MSBios\Resource\Entity\Theme::class,
            //     'form_element' => \MSBios\Resource\Form\ThemeForm::class
            // ]
        ]
    ],
];
