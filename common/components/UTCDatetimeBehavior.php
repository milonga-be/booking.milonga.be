<?php
namespace common\components;

use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\base\Behavior;
use yii\behaviors\AttributeBehavior;

class UTCDatetimeBehavior extends AttributeBehavior
{
    public $createdAtAttribute = 'created_at';
    public $updatedAtAttribute = 'updated_at';
    public $value;

    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAtAttribute, $this->updatedAtAttribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
            ];
        }
    }

    /**
     * {@inheritdoc}
     *
     * In case, when the [[value]] is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
     * will be used as value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            $dateUTC = new \DateTime("now", new \DateTimeZone("UTC"));
            return $dateUTC->format('Y-m-d H:i:s');
        }

        return parent::getValue($event);
    }
}