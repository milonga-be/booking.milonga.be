<?php

use yii\db\Migration;

/**
 * Class m250913_135117_participation_registered
 */
class m250913_135117_participation_registered extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('participation', 'registered', $this->boolean().' DEFAULT 0');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('participation', 'registered');
    }
}
