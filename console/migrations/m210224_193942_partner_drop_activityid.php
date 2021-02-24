<?php

use yii\db\Migration;

/**
 * Class m210224_193942_partner_drop_activityid
 */
class m210224_193942_partner_drop_activityid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-participant-activity_id', 'partner');
        $this->dropColumn('partner', 'activity_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210224_193942_partner_drop_activityid cannot be reverted.\n";

        return false;
    }
}
