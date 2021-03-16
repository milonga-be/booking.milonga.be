<?php

use yii\db\Migration;

/**
 * Class m210316_123700_activity_additional_fields
 */
class m210316_123700_activity_additional_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('activity', 'dance', $this->string(25));
        $this->addColumn('activity', 'level', $this->string(25));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210316_123700_activity_additional_fields cannot be reverted.\n";

        return false;
    }
}
