<?php return array(
    'subscription' => array(
        'can_email_default' => true
    ),
    'router' => array(
        'routes' => array(
            'subscription' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/subscription[/:hashedEmail]', //requires hashed email if visiting from link (without being logged in)
                    'defaults' => array(
                        'controller' => 'subscription',
                        'action'     => 'index',
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
