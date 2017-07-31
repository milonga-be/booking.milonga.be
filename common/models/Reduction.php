<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class Reduction extends ActiveRecord
{
	public static function tableName()
    {
        return 'reduction';
    }
}