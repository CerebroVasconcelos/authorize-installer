<?php

return array(
    'router' => array(
        'routes' => array(
            'authorize_install' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/authorize/install[/:action]',
                    'defaults' => array(
                        'controller' => 'AuthorizeInstaller\Controller\Install',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'AuthorizeInstaller\Controller\Install' => 'AuthorizeInstaller\Controller\Install',
            'AuthorizeInstaller\Controller\ControllerAbstract' => 'AuthorizeInstaller\Controller\ControllerAbstract',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'AuthorizeInstaller\Form\Database' => 'AuthorizeInstaller\Form\Database',
            'AuthorizeInstaller\Form\Authorize' => 'AuthorizeInstaller\Form\Authorize',
            'AuthorizeInstaller\Form\Email' => 'AuthorizeInstaller\Form\Email',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
