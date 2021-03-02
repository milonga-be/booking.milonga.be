<?php

use yii\db\Migration;

/**
 * Class m210302_202008_teacher_fk_event_id
 */
class m210302_202008_teacher_fk_event_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-teacher-event_id', 'teacher', 'event_id', 'event', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210302_202008_teacher_fk_event_id cannot be reverted.\n";

        return false;
    }
}
