<?php
namespace AvdEmail;

return array(
    'router' => array(
        'routes' => array(
            'email' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/email[/:action][/:id]',
                    'defaults' => array(
                        'controller' => 'AvdEmail\Controller\Email',
                        'action' => 'index',
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'doctrine' => array(
        'driver' => array(
             __NAMESPACE__ . '_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/AvdEmail/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ => __NAMESPACE__ . '_annotation_driver'
                )
            )
        )
    )
);
