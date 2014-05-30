<?php

namespace Subscription\Service;

interface SubscriptionInterface
{
    public function generateKey($email);
    public function validateKey($email, $key);
    public function unsubscribe($email, $typeEnum=0, $typeId=0);
    public function subscribe($email, $typeEnum=0, $typeId=0);
    public function canEmail($email, $typeEnum=0, $typeId=0);
}
