<?php

use yii\db\Migration;

/**
 * Class m210225_123248_participation_partner
 */
class m210225_123248_participation_partner extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-participation-participant2_id', 'participation');
        $this->dropColumn('participation', 'partner_id');

        $this->addColumn('partner', 'participation_id', $this->integer());
        $this->addForeignKey('fk-partner-participation_id', 'partner', 'participation_id', 'participation', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210225_123248_participation_partner cannot be reverted.\n";

        return false;
    }
}
