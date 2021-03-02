<?php

use yii\db\Migration;

/**
 * Class m210226_201848_reduction_rule
 */
class m210226_201848_reduction_rule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('reduction', 'reduction_rule');
        $this->createTable('reduction', [
            'id' => $this->primaryKey(),
            'created_at' => $this->datetime().' DEFAULT NULL',
            'updated_at' => $this->datetime().' DEFAULT NULL',
            'event_id' => $this->integer(),
            'title' => $this->string(255)
        ]);
        $this->addForeignKey('fk-reduction-event_id', 'reduction', 'event_id', 'event', 'id');
        $this->addColumn('reduction_rule', 'reduction_id', $this->integer());
        $this->addForeignKey('fk-reduction_rule-reduction_id', 'reduction_rule', 'reduction_id', 'reduction', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210226_201848_reduction_rule cannot be reverted.\n";

        return false;
    }
}
