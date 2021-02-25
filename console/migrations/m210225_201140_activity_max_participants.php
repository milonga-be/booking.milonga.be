<?php

use yii\db\Migration;

/**
 * Class m210225_201140_activity_max_participants
 */
class m210225_201140_activity_max_participants extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('activity', 'max_participants', $this->integer().' DEFAULT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210225_201140_activity_max_participants cannot be reverted.\n";

        return false;
    }
}
