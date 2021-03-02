<?php

use yii\db\Migration;

/**
 * Class m210302_201713_teacher_uuid
 */
class m210302_201713_teacher_uuid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('teacher', 'uuid', $this->string(36).' AFTER updated_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210302_201713_teacher_uuid cannot be reverted.\n";

        return false;
    }
}
