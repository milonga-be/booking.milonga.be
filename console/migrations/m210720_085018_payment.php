<?php

use yii\db\Migration;

/**
 * Class m210720_085018_payment
 */
class m210720_085018_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment',[
            'id' => $this->primaryKey(),
            'created_at' => $this->datetime().' DEFAULT NULL',
            'updated_at' => $this->datetime().' DEFAULT NULL',
            'uuid' => $this->string(36),
            'booking_id' => $this->integer(),
            'amount' => $this->money()
        ]);
        $this->addForeignKey('fk-payment-booking_id', 'payment', 'booking_id', 'booking', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-payment-booking_id', 'payment');
        $this->dropTable('payment');
    }
}
