<?php

use yii\db\Migration;

/**
 * Class m220902_201845_participation_role
 */
class m220902_201845_participation_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('participation', 'role', $this->string(8));// leader or follower
        $this->addColumn('partner', 'role', $this->string(8));// leader or follower
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220902_201845_participation_role cannot be reverted.\n";

        return false;
    }
}
