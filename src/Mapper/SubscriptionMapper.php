<?php

namespace Subscription\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Subscription\Entity\Subscription as EntityPrototype;
use Zend\Db\Sql;

class SubscriptionMapper extends AbstractDbMapper
{
    protected $tableName = 'subscription';

    public function getRecord($email, $typeEnum, $typeId)
    {
        $select = $this->getSelect();
        $select->where(array(
            'email'     => $email,
            'type_enum' => $typeEnum,
            'type_id'   => $typeId,
        ));

        $select->limit(1);
        $result = $this->select($select);

        if (count($result)) {
            return $result->current();
        }

        return false;
    }

    public function getRecordById($id)
    {
        $select = $this->getSelect();

        $where = new Sql\Where();
        $where->equalTo('id', $id);
        $select->limit(1);
        $select->where($where);

        $result = $this->select($select);

        if (count($result)) {
            return $result->current();
        }

        return false;
    }

    public function getRecordsByEmail($email, $subscribed=null)
    {
        $select = $this->getSelect();

        $where = new Sql\Where();
        $where->equalTo('email', $email);
        if ($subscribed !== null) {
            $this->addSubscriptionToWhere($where, $subscribed);
        }
        $select->where($where);

        return $this->select($select);
    }

    protected function addSubscriptionToWhere(Sql\Where $where, $subscribed=null)
    {
        if (!is_bool($subscribed)) {
            throw new \Exception('expecting boolean for subscribe');
        }

        if ($subscribed) {
            $where->greaterThan(
                'subscribe_date',   //field "identifier"
                'unsubscribe_date', //field "identifier"
                Sql\Predicate\Predicate::TYPE_IDENTIFIER,
                Sql\Predicate\Predicate::TYPE_IDENTIFIER
            );
        } else {
            $where->greaterThan(
                'unsubscribe_date', //field "identifier"
                'subscribe_date',   //field "identifier"
                Sql\Predicate\Predicate::TYPE_IDENTIFIER,
                Sql\Predicate\Predicate::TYPE_IDENTIFIER
            );
        }

    }

    public function getRecordsByTypeEnum($typeEnum, $email=null, $subscribed=null)
    {
        $select = $this->getSelect();

        $where = new Sql\Where();
        $where->equalTo('type_enum', $typeEnum);
        if ($email) {
            $where->equalTo('email', $email);
        }
        if ($subscribed !== null) {
            $this->addSubscriptionToWhere($where, $subscribed);
        }
        $select->where($where);

        return $this->select($select);
    }

    public function getRecordsByTypeId($typeEnum=0, $typeId=0, $subscribed=null)
    {
        $select = $this->getSelect();
        $where = new Sql\Where();
        $where->equalTo('type_enum', $typeEnum);
        $where->equalTo('type_id', $typeId);
        if ($subscribed !== null) {
            $this->addSubscriptionToWhere($where, $subscribed);
        }
        $select->where($where);

        return $this->select($select);
    }


    public function persist(EntityPrototype $subscription)
    {
        $record = $this->getRecord(
            $subscription->getEmail(),
            $subscription->getTypeEnum(),
            $subscription->getTypeId()
        );
        if ($record) {
            return $this->update($subscription, ['id' => $record->getId()]);
        }
        return $this->insert($subscription);
    }

    public function getEntityPrototype()
    {
        return new EntityPrototype;
    }
}
