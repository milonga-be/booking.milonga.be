<?php

use yii\db\Migration;

/**
 * Class m220905_190829_participation_has_partner
 */
class m220905_190829_participation_has_partner extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('participation', 'has_partner', $this->boolean().' DEFAULT 0');// leader or follower
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('participation', 'has_partner');
    }
}
