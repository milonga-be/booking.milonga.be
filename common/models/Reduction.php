<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class Reduction extends ActiveRecord
{

	const PCT_WHOLE_PRICE = 'percentage_of_whole_price';
	const REDUCE_EVERY_PRICE = 'reduce_every_price';

	public static function tableName()
    {
        return 'reduction';
    }
}