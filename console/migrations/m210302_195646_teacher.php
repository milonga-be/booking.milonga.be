<?php

use yii\db\Migration;

/**
 * Class m210302_195646_teacher
 */
class m210302_195646_teacher extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('teacher', [
            'id' => $this->primaryKey(),
            'created_at' => $this->datetime().' DEFAULT NULL',
            'updated_at' => $this->datetime().' DEFAULT NULL',
            'event_id' => $this->integer(),
            'name' => $this->string(255)
        ]);

        $this->addColumn('activity', 'teacher_id', $this->integer());
        $this->addForeignKey('fk-activity-teacher_id', 'activity', 'teacher_id', 'teacher', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210302_195646_teacher cannot be reverted.\n";

        return false;
    }
}
