<?php

use yii\db\Migration;

/**
 * Class m220922_120011_event_closed
 */
class m220922_120011_event_closed extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'closed', $this->boolean().' DEFAULT 0');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'closed');
    }
}
