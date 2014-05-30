<?php

namespace Subscription;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src',
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'subscription_config' => function ($sm) {
                    $config = $sm->get('Config');
                    if (!isset($config['subscription']) || !is_array($config['subscription'])) {
                        throw new \Exception("config required for subscription service");
                    }
                    $subscription = $config['subscription'];
                    if (!isset($subscription['hash_salt']) || !$subscription['hash_salt']) {
                        throw new \Exception('missing or empty hash_salt in subscription config');
                    }
                    return $subscription;
                },
                'Subscription\Mapper\SubscriptionMapper' => function ($sm) {
                    $mapper = new Mapper\SubscriptionMapper;
                    $mapper->setDbAdapter($sm->get('subscription_db_adapter'));
                    $mapper->setEntityPrototype(new Entity\Subscription);
                    return $mapper;
                }
            ),
        );
    }
}
