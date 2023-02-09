<?php

use yii\db\Migration;

/**
 * Class m230206_192532_reduction_promocode
 */
class m230206_192532_reduction_promocode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reduction', 'promocode', $this->string(64).' AFTER name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('reduction', 'promocode');
    }
}
