<?php

namespace Subscription\Service;

interface SubscriptionInterface
{
    public function generateKey($email);
    public function validateKey($email, $key);
    public function unsubscribe($email, $typeEnum=0, $typeId=0);
    public function subscribe($email, $typeEnum=0, $typeId=0);
    public function canEmail($email, $typeEnum=0, $typeId=0);
    //get records for a specific type enum and identifier for single email
    public function getRecord($email, $typeEnum=0, $typeId=0, $new=false);
    //get all records for a specific email address, optionally get subscribed/unsubscribed only
    public function getRecordsByEmail($email, $subscribed=null);
    //get all records for a specific type enum, optional email, optionally get subscribed/unsubscribed only
    public function getRecordsByTypeEnum($typeEnum, $email=null, $subscribed=null);
    //get all records for a specific type enum and identifier (all emails), optionally get subscribed/unsubscribed only
    public function getRecordsByTypeId($typeEnum=0, $typeId=0, $subscribed=null);
}
