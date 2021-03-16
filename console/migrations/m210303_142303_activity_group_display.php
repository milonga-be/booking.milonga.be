<?php

use yii\db\Migration;

/**
 * Class m210303_142303_activity_group_display
 */
class m210303_142303_activity_group_display extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('activity_group', 'display', $this->string(10));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210303_142303_activity_group_display cannot be reverted.\n";

        return false;
    }
}
