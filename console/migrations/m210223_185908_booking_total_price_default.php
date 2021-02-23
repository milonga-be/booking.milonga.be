<?php

use yii\db\Migration;

/**
 * Class m210223_185908_booking_total_price_default
 */
class m210223_185908_booking_total_price_default extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('UPDATE booking SET total_price = 0 WHERE total_price IS NULL;');
        $this->alterColumn('booking', 'total_price', $this->money().' DEFAULT 0 NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210223_185908_booking_total_price_default cannot be reverted.\n";

        return false;
    }
}
