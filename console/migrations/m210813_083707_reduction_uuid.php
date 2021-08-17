<?php

use yii\db\Migration;

/**
 * Class m210813_083707_reduction_uuid
 */
class m210813_083707_reduction_uuid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reduction', 'uuid', $this->string(36).' AFTER updated_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210813_083707_reduction_uuid cannot be reverted.\n";

        return false;
    }
}
