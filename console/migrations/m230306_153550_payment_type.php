<?php

use yii\db\Migration;

/**
 * Class m230306_153550_payment_type
 */
class m230306_153550_payment_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment', 'type', $this->string(10). ' DEFAULT "transfer"');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment', 'type');
    }
}
