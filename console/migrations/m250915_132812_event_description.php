<?php

use yii\db\Migration;

/**
 * Class m250915_132812_event_description
 */
class m250915_132812_event_description extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'description', $this->text());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('event', 'description');

        return true;
    }
}
