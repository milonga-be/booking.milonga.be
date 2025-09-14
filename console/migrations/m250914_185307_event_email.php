<?php

use yii\db\Migration;

/**
 * Class m250914_185307_event_email
 */
class m250914_185307_event_email extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'email', $this->string());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'email');

        return true;
    }
}
