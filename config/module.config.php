<?php return array(
    'subscription' => array(
        'can_email_default' => true
    ),
    'router' => array(
        'routes' => array(
            'subscription' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/subscription',
                    'defaults' => array(
                        'controller' => 'subscription',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'external' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:hashedEmail',
                            'defaults' => array(
                                'controller' => 'subscription',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                    'subscription' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/:subscriptionId/:hashedEmail',
                            'defaults' => array(
                                'controller' => 'subscription',
                                'action'     => 'subscription',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'subscription'    => 'Subscription\Controller\SubscriptionController',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(
            'subscription_db_adapter' => 'Zend\Db\Adapter\Adapter'
        ),
        'invokables' => array(
            'Subscription\Service\SubscriptionService' => 'Subscription\Service\SubscriptionService',
        ),
    ),
);
