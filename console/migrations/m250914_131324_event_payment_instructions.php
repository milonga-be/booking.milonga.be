<?php

use yii\db\Migration;

/**
 * Class m250914_131324_event_payment_instructions
 */
class m250914_131324_event_payment_instructions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'payment_instructions', 'text');
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'payment_instructions');
        return true;
    }
}
