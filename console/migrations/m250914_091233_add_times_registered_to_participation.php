<?php

use yii\db\Migration;

/**
 * Class m250914_091233_add_times_registered_to_participation
 */
class m250914_091233_add_times_registered_to_participation extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%participation}}', 'times_registered', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%participation}}', 'times_registered');
    }
}
