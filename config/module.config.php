<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\CPanel\Doctrine;

return [

    'doctrine' => [
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    Subscriber\TimestampableSubscriber::class
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>
                Factory\IndexControllerFactory::class,
            Controller\LayoutController::class =>
                Factory\ControllerFactory::class,
            Controller\ModuleController::class =>
                Factory\ControllerFactory::class,
            Controller\PageTypeController::class =>
                Factory\ControllerFactory::class,
            Controller\RouteController::class =>
                Factory\ControllerFactory::class,
            Controller\SettingController::class =>
                Factory\ControllerFactory::class,
            Controller\ThemeController::class =>
                Factory\ControllerFactory::class,
        ],
        'aliases' => [
            \MSBios\CPanel\Controller\IndexController::class =>
                Controller\IndexController::class,
            \MSBios\CPanel\Controller\LayoutController::class =>
                Controller\LayoutController::class,
            \MSBios\CPanel\Controller\ModuleController::class =>
                Controller\ModuleController::class,
            \MSBios\CPanel\Controller\PageTypeController::class =>
                Controller\PageTypeController::class,
            \MSBios\CPanel\Controller\RouteController::class =>
                Controller\RouteController::class,
            \MSBios\CPanel\Controller\SettingController::class =>
                Controller\SettingController::class,
            \MSBios\CPanel\Controller\ThemeController::class =>
                Controller\ThemeController::class
        ],
    ],

    'form_elements' => [
        'aliases' => [
            Controller\LayoutController::class =>
                \MSBios\Resource\Form\LayoutForm::class,
            Controller\ModuleController::class =>
                \MSBios\Resource\Form\ModuleForm::class,
            Controller\PageTypeController::class =>
                \MSBios\Resource\Form\PageTypeForm::class,
            Controller\ThemeController::class =>
                \MSBios\Resource\Form\ThemeForm::class
        ],
    ],

    \MSBios\Theme\Module::class => [

        'themes' => [
            'limitless' => [
                // Template Map
                'template_map' => [
                    'ms-bios/c-panel/doctrine/layout/add' =>
                        __DIR__ . '/../themes/limitless/view/ms-bios/c-panel/doctrine/layout/form.phtml',
                    'ms-bios/c-panel/doctrine/layout/edit' =>
                        __DIR__ . '/../themes/limitless/view/ms-bios/c-panel/doctrine/layout/form.phtml',

                    'ms-bios/c-panel/doctrine/module/add' =>
                        __DIR__ . '/../themes/limitless/view/ms-bios/c-panel/doctrine/module/form.phtml',
                    'ms-bios/c-panel/doctrine/module/edit' =>
                        __DIR__ . '/../themes/limitless/view/ms-bios/c-panel/doctrine/module/form.phtml',

                    'ms-bios/c-panel/doctrine/page-type/add' =>
                        __DIR__ . '/../themes/limitless/view/ms-bios/c-panel/doctrine/page-type/form.phtml',
                    'ms-bios/c-panel/doctrine/page-type/edit' =>
                        __DIR__ . '/../themes/limitless/view/ms-bios/c-panel/doctrine/page-type/form.phtml',

                    'ms-bios/c-panel/doctrine/theme/add' =>
                        __DIR__ . '/../themes/limitless/view/ms-bios/c-panel/doctrine/theme/form.phtml',
                    'ms-bios/c-panel/doctrine/theme/edit' =>
                        __DIR__ . '/../themes/limitless/view/ms-bios/c-panel/doctrine/theme/form.phtml',
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
