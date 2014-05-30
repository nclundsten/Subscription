<?php

namespace Subscription\Entity;

class Subscription
{
    protected $id;
    protected $email;
    protected $typeEnum = 0;
    protected $typeId = 0;
    protected $unsubscribeDate = 0;
    protected $subscribeDate = 0;

    /**
     * @return id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return typeEnum
     */
    public function getTypeEnum()
    {
        return $this->typeEnum;
    }

    /**
     * @param $typeEnum
     * @return self
     */
    public function setTypeEnum($typeEnum)
    {
        $this->typeEnum = $typeEnum;
        return $this;
    }

    /**
     * @return typeId
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param $typeId
     * @return self
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        return $this;
    }

    /**
     * @return unsubscribeDate
     */
    public function getUnsubscribeDate()
    {
        return $this->unsubscribeDate;
    }

    /**
     * @param $unsubscribeDate
     * @return self
     */
    public function setUnsubscribeDate($unsubscribeDate)
    {
        $this->unsubscribeDate = $unsubscribeDate;
        return $this;
    }

    /**
     * @return subscribeDate
     */
    public function getSubscribeDate()
    {
        return $this->subscribeDate;
    }

    /**
     * @param $subscribeDate
     * @return self
     */
    public function setSubscribeDate($subscribeDate)
    {
        $this->subscribeDate = $subscribeDate;
        return $this;
    }
}
