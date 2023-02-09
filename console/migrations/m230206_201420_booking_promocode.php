<?php

use yii\db\Migration;

/**
 * Class m230206_201420_booking_promocode
 */
class m230206_201420_booking_promocode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('booking', 'promocode', $this->string(64).' AFTER email');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('booking', 'promocode');
    }
}
