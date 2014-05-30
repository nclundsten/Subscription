<?php

namespace Subscription\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class SubscriptionService implements SubscriptionInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected $config;
    protected $mapper;

    public function generateKey($email)
    {
        $salt = (string) $this->getConfig()['hash_salt'];

        return md5($salt.$email.'pePpeR');
    }

    public function validateKey($email, $key)
    {
        if ($key === $this->generateKey($email)) {
            return true;
        }
        return false;
    }

    public function unsubscribe($email, $typeEnum=0, $typeId=0)
    {
        $record = $this->getRecord($email, $typeEnum, $typeId, true);
        $record->setUnsubscribeDate(time());
        $this->getMapper()->persist($record);
    }

    public function subscribe($email, $typeEnum=0, $typeId=0)
    {
        $record = $this->getRecord($email, $typeEnum, $typeId, true);
        $record->setSubscribeDate(time());

        $this->getMapper()->persist($record);
    }

    public function getRecord($email, $typeEnum=0, $typeId=0, $new=false)
    {
        $record = $this->getMapper()->getRecord($email, $typeEnum, $typeId);
        if (!$record && $new) {
            $record = $this->getMapper()->getEntityPrototype();
            $record->setEmail($email);
            $record->setTypeEnum($typeEnum);
            $record->setTypeId($typeId);
        }
        return $record;
    }

    public function getRecordsByEmail($email, $subscribed=null)
    {
        return $this->getMapper()->getRecordsByEmail($email, $subscribed);
    }

    public function getRecordsByTypeEnum($typeEnum, $email=null, $subscribed=null)
    {
        return $this->getMapper()->getRecordsByTypeEnum($typeEnum, $email, $subscribed);
    }

    public function getRecordsByTypeId($typeEnum=0, $typeId=0, $subscribed=null)
    {
        return $this->getMapper()->getRecordsByTypeId($typeEnum, $typeId, $subscribed);
    }

    public function persistSubscriptions($email, array $subscriptionArray)
    {
        foreach ($subscriptionArray as $subscription) {
            $typeEnum = isset($subscription['type_enum'])
                ? $subscription['type_enum']
                : 0;
            $typeId = isset($subscription['type_id'])
                ? $subscription['type_id']
                : 0;
            if ($subscription['subscribed']) {
                $this->subscribe($email, $typeEnum, $typeId);
            } else {
                $this->unsubscribe($email, $typeEnum, $typeId);
            }
        }
    }

    public function canEmail($email, $typeEnum=0, $typeId=0, $default=null)
    {
        $canEmailDefault = ($default !== null) ? $default : $this->getConfig()['can_email_default'];

        $record = $this->getMapper()->getRecord($email, $typeEnum, $typeId);
        if ($record) {
            //did they subscribe last?
            if ($record->getSubscribeDate() > $record->getUnsubscribeDate()) {
                return true;
            }
            return false;
        }

        //maybe they are subscribed/unsubscribed to the whole site/company
        if ($typeEnum != 0 && $typeId != 0) {
            $record = $this->getMapper()->getRecord($email, 0, 0);
            if ($record) {
                //did they subscribe last?
                if ($record->getSubscribeDate() > $record->getUnsubscribeDate()) {
                    return true;
                }
                return false;
            }
        }

        return $canEmailDefault;
    }

    public function getConfig()
    {
        if (null === $this->config) {
            $this->config = $this->getServiceLocator()->get('subscription_config');
        }
        return $this->config;
    }

    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    public function getMapper()
    {
        if (null === $this->mapper) {
            $this->mapper = $this->getServiceLocator()
                ->get('Subscription\Mapper\SubscriptionMapper');
        }
        return $this->mapper;
    }

    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
}
