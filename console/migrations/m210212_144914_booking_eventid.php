<?php

use yii\db\Migration;

/**
 * Class m210212_144914_booking_eventid
 */
class m210212_144914_booking_eventid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('booking', 'event_id', $this->integer().' AFTER updated_at');

        $this->addForeignKey('fk-booking-event_id', 'booking', 'event_id', 'event', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210212_144914_booking_eventid cannot be reverted.\n";

        return false;
    }
}
