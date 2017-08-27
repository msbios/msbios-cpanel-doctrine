<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel;

use Zend\Router\Http\Segment;

$CPANEL = getenv('APPLICATION_PANEL') ? : 'cpanel';

return [

    'router' => [
        'routes' => [
            'cpanel' => [
                'type' => Segment::class,
                'options' => [
                    'route' => "/[:locale/]{$CPANEL}[/]",
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                        'locale' => 'en_US',

                        // MSBios\Theme
                        'theme_identifier' => 'limitless',
                        // 'theme_identifier' => 'paper',
                        // 'layout_identifier' => 'limitless'
                    ],
                    'constraints' => [
                        'locale' => '(?i)[a-z]{2,3}(?:_[a-z]{2})?',
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'authentication' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => ':action[/]',
                            'constraints' => [
                                'action' => 'login|logout'
                            ]
                        ]
                    ],
                    'layout' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'layout[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\LayoutController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    'module' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'module[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\ModuleController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    'page-type' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'page-type[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\PageTypeController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    'route' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'route[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\RouteController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ]
                        ]
                    ],
                    'setting' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'setting[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\SettingController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ],
                        ]
                    ],
                    'theme' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => 'theme[/[:action[/[:id[/]]]]]',
                            'defaults' => [
                                'controller' => Controller\ThemeController::class,
                            ],
                            'constraints' => [
                                'action' => 'add|edit|drop',
                                'id' => '[0-9]+'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [

        'abstract_factories' => [
            // Mvc\Controller\LazyControllerAbstractFactory::class,
        ],

        'factories' => [
            Controller\IndexController::class => Factory\LazyActionControllerFactory::class,
            Controller\LayoutController::class => Factory\LazyActionControllerFactory::class,
            Controller\ModuleController::class => Factory\LazyActionControllerFactory::class,
            Controller\PageTypeController::class => Factory\LazyActionControllerFactory::class,
            Controller\RouteController::class => Factory\LazyActionControllerFactory::class,
            Controller\SettingController::class => Factory\LazyActionControllerFactory::class,
            Controller\ThemeController::class => Factory\LazyActionControllerFactory::class,
        ]
    ],

    'service_manager' => [
        'invokables' => [
            Listener\TranslatorListener::class,

            // Widgets
            Widget\AreYouSureDropWidget::class
        ],
        'factories' => [
            Module::class => Factory\ModuleFactory::class,
            Navigation\Sidebar::class => Factory\NavigationFactory::class
        ]
    ],

    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            ],
        ],
    ],

    'navigation' => [
        Navigation\Sidebar::class => [
            'dashboard' => [
                'label' => _('Dashboard'),
                'route' => 'cpanel',
                'class' => 'icon-home',
                'order' => 100
            ],
            'setting' => [
                'label' => _('System'),
                'uri' => '#',
                'class' => 'icon-gear',
                'order' => 100500,
                'pages' => [
                    'layout' => [
                        'label' => _('Layouts'),
                        'route' => 'cpanel/layout',
                    ],
                    'module' => [
                        'label' => _('Modules'),
                        'route' => 'cpanel/module',
                    ],
                    'page-type' => [
                        'label' => _('Page Types'),
                        'route' => 'cpanel/page-type',
                    ],
                    'route' => [
                        'label' => _('Routes'),
                        'route' => 'cpanel/route',
                    ],
                    'setting' => [
                        'label' => _('Setting'),
                        'route' => 'cpanel/setting',
                    ],
                    'theme' => [
                        'label' => _('Themes'),
                        'route' => 'cpanel/theme',
                    ],
                ]
            ]
        ],
    ],

    \MSBios\Theme\Module::class => [

        'themes' => [
            'limitless' => [
                'identifier' => 'limitless',
                'title' => 'Limitless Application Theme',
                'description' => 'Limitless Application Theme Description',
                'template_map' => [
                ],
                'template_path_stack' => [
                    __DIR__ . '/../themes/limitless/view/',
                ],
                'controller_map' => [
                ],
                'translation_file_patterns' => [
                    [
                        'type' => 'gettext',
                        'base_dir' => __DIR__ . '/../themes/limitless/language/',
                        'pattern' => '%s.mo',
                    ],
                ],
                'widget_manager' => [
                    'template_map' => [
                    ],
                    'template_path_stack' => [
                        __DIR__ . '/../themes/limitless/widget/'
                    ],
                ],
            ],

            'paper' => [
                'identifier' => 'paper',
                'title' => 'Paper CPanel Theme',
                'description' => 'Paper CPanel Theme',
                'template_map' => [
                ],
                'template_path_stack' => [
                    __DIR__ . '/../themes/paper/view/',
                ],
                'controller_map' => [
                ],
                'translation_file_patterns' => [
                    [
                        'type' => 'gettext',
                        'base_dir' => __DIR__ . '/../themes/paper/language/',
                        'pattern' => '%s.mo',
                    ],
                ],
            ]
        ]
    ],

    \MSBios\Guard\Module::class => [

        'role_providers' => [
            \MSBios\Guard\Provider\RoleProvider::class => [
            ]
        ],

        'resource_providers' => [
            \MSBios\Guard\Provider\ResourceProvider::class => [

                Controller\IndexController::class => [],
                Controller\LayoutController::class => [],
                Controller\ModuleController::class => [],
                Controller\PageTypeController::class => [],
                Controller\RouteController::class => [],
                Controller\SettingController::class => [],
                Controller\ThemeController::class => [],

                'DASHBOARD' => [
                    'SIDEBAR' => [],
                ],
            ],
        ],

        'rule_providers' => [
            \MSBios\Guard\Provider\RuleProvider::class => [
                'allow' => [
                    [['DEVELOPER'], Controller\IndexController::class],
                    [['DEVELOPER'], Controller\LayoutController::class],
                    [['DEVELOPER'], Controller\ModuleController::class],
                    [['DEVELOPER'], Controller\PageTypeController::class],
                    [['DEVELOPER'], Controller\RouteController::class],
                    [['DEVELOPER'], Controller\SettingController::class],
                    [['DEVELOPER'], Controller\ThemeController::class],
                    [['DEVELOPER'], 'SIDEBAR'],
                ],
                'deny' => []
            ]
        ],
    ],

    Module::class => [

        'listeners' => [
            [
                'listener' => Listener\TranslatorListener::class,
                'method' => 'onDispatch',
                'event' => \Zend\Mvc\MvcEvent::EVENT_DISPATCH,
                'priority' => 10,
            ],
        ],

        'controllers' => [ // key controller
            Controller\LayoutController::class => [
                'resource' => Controller\LayoutController::class,
                'route_name' => 'cpanel/layout',
                'resource_class' => \MSBios\Resource\Entity\Layout::class,
                'form_element' => \MSBios\Resource\Form\LayoutForm::class
            ],
            Controller\ModuleController::class => [
                'resource' => Controller\ModuleController::class,
                'route_name' => 'cpanel/module',
                'resource_class' => \MSBios\Resource\Entity\Module::class,
                'form_element' => \MSBios\Resource\Form\ModuleForm::class
            ],
            Controller\PageTypeController::class => [
                'resource' => Controller\PageTypeController::class,
                'route_name' => 'cpanel/page-type',
                'resource_class' => \MSBios\Resource\Entity\PageType::class,
                // 'form_element' => \MSBios\Resource\Form\UserForm::class
            ],
            Controller\RouteController::class => [
                'resource' => Controller\RouteController::class,
                'route_name' => 'cpanel/route',
                'resource_class' => \MSBios\Resource\Entity\PageType::class,
                // 'form_element' => \MSBios\Resource\Form\UserForm::class
            ],
            Controller\SettingController::class => [
                'resource' => Controller\SettingController::class,
                'route_name' => 'cpanel/setting',
                'resource_class' => \MSBios\Resource\Entity\Setting::class,
                // 'form_element' => \MSBios\Resource\Form\UserForm::class
            ],
            Controller\ThemeController::class => [
                'resource' => Controller\ThemeController::class,
                'route_name' => 'cpanel/theme',
                'resource_class' => \MSBios\Resource\Entity\Theme::class,
                'form_element' => \MSBios\Resource\Form\ThemeForm::class
            ]
        ]
    ],
];
