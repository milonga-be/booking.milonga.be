<?php

use yii\db\Migration;

/**
 * Class m230209_082557_reduction_rule_activity_id
 */
class m230209_082557_reduction_rule_activity_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reduction_rule', 'activity_id', $this->integer().' AFTER activity_group_id');
        $this->addForeignKey('fk-reduction_rule-activity_id', 'reduction_rule', 'activity_id', 'activity', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230209_082557_reduction_rule_activity_id cannot be reverted.\n";

        return false;
    }
}
