<?php

use yii\db\Migration;

/**
 * Class m210227_170719_booking_confirmed
 */
class m210227_170719_booking_confirmed extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('booking', 'confirmed', $this->boolean().' DEFAULT 1');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210227_170719_booking_confirmed cannot be reverted.\n";

        return false;
    }
}
