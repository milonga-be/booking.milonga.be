<?php

use yii\db\Migration;

/**
 * Class m210213_201907_partner
 */
class m210213_201907_partner extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('ALTER TABLE participant RENAME partner;');
        $this->dropForeignKey('fk-participation-participant1_id', 'participation');
        $this->dropColumn('participation', 'participant1_id');
        $this->renameColumn('participation', 'participant2_id', 'partner_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210213_201907_partner cannot be reverted.\n";

        return false;
    }
}
