<?php

use yii\db\Migration;

/**
 * Class m210213_202920_partner2
 */
class m210213_202920_partner2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-participant-couple_participant_id', 'partner');
        $this->dropColumn('partner', 'couple_participant_id');
        $this->dropColumn('partner', 'email');
        $this->dropColumn('partner', 'phone');

        $this->dropForeignKey('fk-booking_participations-booking_id', 'booking_participations');
        $this->dropForeignKey('fk-booking_participations-booking_participation_id', 'booking_participations');
        $this->dropTable('booking_participations');
        $this->addColumn('participation', 'booking_id', $this->integer());
        $this->addForeignKey('fk-participations-booking_id', 'participation', 'booking_id', 'booking', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210213_202920_partner2 cannot be reverted.\n";

        return false;
    }
}
