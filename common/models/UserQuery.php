<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[DataUser]].
 *
 * @see DataUser
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DataUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DataUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
