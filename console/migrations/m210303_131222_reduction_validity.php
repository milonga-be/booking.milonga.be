<?php

use yii\db\Migration;

/**
 * Class m210303_131222_reduction_validity
 */
class m210303_131222_reduction_validity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reduction', 'validity_start', $this->date().' NULL');
        $this->addColumn('reduction', 'validity_end', $this->date(). ' NULL');
        $this->dropColumn('reduction_rule', 'validity_start');
        $this->dropColumn('reduction_rule', 'validity_end');
        $this->dropColumn('reduction_rule', 'event_id');
        $this->dropColumn('reduction_rule', 'description');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210303_131222_reduction_validity cannot be reverted.\n";

        return false;
    }
}
