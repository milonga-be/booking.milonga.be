<?php

use yii\db\Migration;

/**
 * Class m220929_124116_partner_booking_id
 */
class m220929_124116_partner_booking_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('booking', 'partner_booking_id', $this->integer());
        $this->addForeignKey('fk-booking-partner_booking_id', 'booking', 'partner_booking_id', 'booking', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220929_124116_partner_booking_id cannot be reverted.\n";

        return false;
    }
}
