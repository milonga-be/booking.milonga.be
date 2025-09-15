<?php

use yii\db\Migration;

/**
 * Class m250915_131714_event_website
 */
class m250915_131714_event_website extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'website', $this->string());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'website');

        return true;
    }
}
