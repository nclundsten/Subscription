<?php

namespace Subscription\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;
use Subscription\Entity\Subscription as EntityPrototype;

class SubscriptionMapper extends AbstractDbMapper
{
    protected $tableName = 'unsubscribe';

    public function getRecord($email, $enumType, $typeId)
    {
        //TODO dev only
        return false;



        $select = $this->getSelect();
        $select->where(array(
            'email'     => $email,
            'enum_type' => $enumType,
            'type_id'   => $typeId,
        ));
        $select->limit(1);
        $result = $this->select($select);

        //TODO: hydration?
        if (count($result)) {
            return $result->current();
        }

        return false;
    }
}
