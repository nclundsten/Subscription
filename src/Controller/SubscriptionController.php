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
        //TODO: prg
        $prg = $_POST;
        $prg = array_merge($prg, $_GET);

        $email = isset($prg['email']) ? $prg['email'] : false;
        $hashedEmail = $this->params('hashedEmail') ?: false;


        if ($hashedEmail) {
            if (!$email) {
                $this->flashMessenger()->addErrorMessage('There was an error processing your request.');
                return $this->redirect()->toRoute('home');
            }

            if (!$this->getSubscriptionService()->validateKey($email, $hashedEmail)) {
                $this->flashMessenger()->addErrorMessage('There was an error processing your request.');
                return $this->redirect()->toRoute('home');
            }
            $url = $this->url()->fromRoute('subscription/external', ['hashedEmail' => $hashedEmail]) . '?email=' . $email;
        } elseif ($this->getZfcUserService()->getIdentity() == false) {
            $this->flashMessenger()->addErrorMessage('There was an error processing your request.');
            return $this->redirect()->toRoute('home');
        }

        //all good, let er rip
        $email = ($email) ?: $this->getZfcUserService()->getIdentity()->getEmail();

        if (isset($prg['subscriptions']) && is_array($prg['subscriptions'])) {
            $this->getSubscriptionService()
                ->persistSubscriptions($email, $prg['subscriptions']);
            $this->flashMessenger()->addSuccessMessage('Subscription preferences saved.');
        }


        $subscriptions = $this->getSubscriptionService()
            ->getRecordsByEmail($email);
        return new ViewModel(array(
            'subscriptions' => $subscriptions,
            'email'         => $email,
            'url'           => ($url) ?: '/subscription',
        ));
    }


    public function subscriptionAction()
    {
        //TODO: prg
        $prg = $_POST;
        $prg = array_merge($prg, $_GET);

        $subscriptionId = $this->params('subscriptionId') ?: false;
        $hashedEmail    = $this->params('hashedEmail') ?: false;
        $email = isset($prg['email']) ? $prg['email'] : false;

        if (!$subscriptionId || !$hashedEmail || !$email) {
            $this->flashMessenger()->addErrorMessage('There was an error processing your request.');
            return $this->redirect()->toRoute('home');
        }

        if (!$this->getSubscriptionService()->validateKey($email, $hashedEmail)) {
            $this->flashMessenger()->addErrorMessage('There was an error processing your request.');
            return $this->redirect()->toRoute('home');
        }

        $url = $this->url()->fromRoute('subscription/external', ['hashedEmail' => $hashedEmail]) . '?email=' . $email;
        $subscription = $this->getSubscriptionService()
            ->getRecordById($subscriptionId);
        return new ViewModel(array(
            'subscription' => $subscription,
            'email'        => $email,
            'url'          => $url,
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
