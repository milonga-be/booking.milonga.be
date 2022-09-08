<?php

use yii\db\Migration;

/**
 * Class m220907_144216_participation_quantity
 */
class m220907_144216_participation_quantity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('participation', 'quantity', $this->integer(11).' DEFAULT 1');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('participation', 'quantity');
    }
}
