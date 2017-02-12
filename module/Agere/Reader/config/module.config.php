<?php
namespace Agere\Reader;

return [
    'sources' => [
        'unian' => [
            'unian_news' 	 => '', //your rss feed
            'unian_business' => '' //your rss feed
        ],
    ],
    'service_manager' => array(
        'aliases' => [
            'RssSyncService' => Service\RssSyncService::class,
        ],
        'invokables' => [
            Model\PostsSource::class => Model\PostsSource::class,
            //Service\RssSyncService::class => Service\RssSyncService::class,
        ],
        'factories' => [
            Service\RssSyncService::class => Service\Factory\RssSyncServiceFactory::class,
        ],
    ),
    'controllers' => [
        'aliases' => [
            'rss' => Controller\ReaderController::class,
        ],
        'factories' => [
            Controller\ReaderController::class => Controller\Factory\ReaderControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'rss\reader' => [
                'type' => 'segment',
                'options' => [
                    'route'    => '/rss',
                    'defaults' => [
                        'controller' => Controller\ReaderController::class,
                        'action'     => 'sync',
                    ],
                ],
            ],
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Model']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Model' => __NAMESPACE__ . '_driver'
                ]
            ],

            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\YamlDriver',
                'cache' => 'array',
                'extension' => '.dcm.yml',
                'paths' => array(__DIR__ . '/yaml')
            ),
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'rss\cron' => [
                    'options' => [
                        'route'    => 'reader sync',
                        'defaults' => [
                            'controller' => Controller\ReaderController::class,
                            'action'     => 'sync'
                        ]
                    ]
                ]
            ]
        ]
    ],
];