<?php

namespace Subscription\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use SubscriptionService\Service;
use Zend\View\Model\ViewModel;

class SubscriptionController extends AbstractActionController
{
    protected $subscriptionService;
    protected $zfcUserService;

    public function indexAction()
    {

        $prg = $_POST;
        //TODO: prg
        //$prg = $this->getParams()->fromPost();

        //$prg = array(
        //    //'email' => 'nigel.lundsten@gmail.com',
        //    'subscriptions' => array(
        //        array(
        //            'type_enum' => 1,
        //            'type_id' => 2,
        //            'subscribed' => true
        //        ),
        //        array(
        //            'type_enum' => 2,
        //            'type_id' => 1,
        //            'subscribed' => false
        //        ),
        //    ),
        //);
        //
        //$prg = array();

        if ($this->params('hashedEmail') && !isset($prg['email'])) {
            $this->flashMessenger()->addErrorMessage('There was an error processing your request.');
            $this->redirect()->toRoute('home');
        }

        if ($this->params('hashedEmail') && isset($prg['email'])) {
            $hash = $this->params('hashedEmail');
            $email = $prg['email'];
            if (!$this->getSubscriptionService()->validateKey($email, $hash)) {
                $this->flashMessenger()->addErrorMessage('There was an error processing your request.');
                $this->redirect()->toRoute('home');
            }
        } elseif($this->getZfcUserService()->getIdentity() == false) {
            $this->flashMessenger()->addErrorMessage('There was an error processing your request.');
            $this->redirect()->toRoute('home');
        }

        //all good, let er rip

        if (!isset($email)) {
            $email = $this->getZfcUserService()->getIdentity()->getEmail();
        };

        if (isset($prg['subscriptions']) && is_array($prg['subscriptions'])) {
            $this->getSubscriptionService()
                ->persistSubscriptions($email, $prg['subscriptions']);
            $this->flashMessenger()->addSuccessMessage('Subscription preferences saved.');
        }

        $subscriptions = $this->getSubscriptionService()
            ->getRecordsByEmail($email);
        error_reporting(1);
        return new ViewModel(array(
            'subscriptions' => $subscriptions,
            'email'         => $email,
        ));
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
    public function setSubscriptionService(Service\SubscriptionInterface $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
        return $this;
    }

    /**
     * @return zfcUserService
     */
    public function getZfcUserService()
    {
        if (null === $this->zfcUserService) {
            $this->zfcUserService = $this->getServiceLocator()
                ->get('zfcuser_auth_service');
        }
        return $this->zfcUserService;
    }

    /**
     * @param $zfcUserService
     * @return self
     */
    public function setZfcUserService($zfcUserService)
    {
        $this->zfcUserService = $zfcUserService;
        return $this;
    }
}
