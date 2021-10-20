<?php

use yii\db\Migration;

/**
 * Class m211020_094736_event_banner
 */
class m211020_094736_event_banner extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('event', 'banner', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211020_094736_event_banner cannot be reverted.\n";

        return false;
    }
}
