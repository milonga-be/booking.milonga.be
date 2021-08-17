<?php

use yii\db\Migration;

/**
 * Class m210813_082804_reduction_name
 */
class m210813_082804_reduction_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('reduction', 'title', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('reduction', 'name', 'title');
    }
}
