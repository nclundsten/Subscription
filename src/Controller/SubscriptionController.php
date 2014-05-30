<?php

namespace Subscription\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class SubscriptionController extends AbstractActionController
{
    protected $subscriptionService;
    public function indexAction()
    {
        $ss = $this->getSubscriptionService();
        var_dump($ss->canEmail('nigel.lundsten@gmail.com', 0, 0));
        die();
    }

    /**
     * @return subscriptionService
     */
    public function getSubscriptionService()
    {
        if (null === $this->subscriptionService) {
            $this->subscriptionService = $this->getServiceLocator()
                ->get('Subscription\Service\SubscriptionService');
        }
        return $this->subscriptionService;
    }

    /**
     * @param $subscriptionService
     * @return self
     */
    public function setSubscriptionService($subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
        return $this;
    }
}
