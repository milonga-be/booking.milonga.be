<?php

use yii\db\Migration;

/**
 * Class m221013_090310_booking_total_paid
 */
class m221013_090310_booking_total_paid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('booking', 'total_paid', $this->money().' DEFAULT 0 AFTER total_price');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('booking', 'total_paid');
    }
}
