<?php

use yii\db\Migration;

/**
 * Class m210818_141923_reduction_exclusion
 */
class m210818_141923_reduction_exclusion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('reduction_incompatibility',[
            'reduction_id' => $this->integer(11),
            'incompatible_reduction_id' => $this->integer(11),
            'created_at' => $this->timestamp().' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'PRIMARY KEY(reduction_id, incompatible_reduction_id)',
        ]);
        $this->addForeignKey('fk-reduction_incompatibility-reduction_id', 'reduction_incompatibility', 'reduction_id', 'reduction', 'id');
        $this->addForeignKey('fk-reduction_incompatibility-incompatible_reduction_id', 'reduction_incompatibility', 'incompatible_reduction_id', 'reduction', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-reduction_incompatibility-reduction_id', 'reduction_incompatibility');
        $this->dropForeignKey('fk-reduction_incompatibility-incompatible_reduction_id', 'reduction_incompatibility');
        $this->dropTable('reduction_incompatibility');
    }
}
