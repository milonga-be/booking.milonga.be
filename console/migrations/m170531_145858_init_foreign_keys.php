<?php

use yii\db\Migration;

class m170531_145858_init_foreign_keys extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk-event-user_id', 'event', 'user_id', 'user', 'id');
        $this->addForeignKey('fk-activity_group-event_id', 'activity_group', 'event_id', 'event', 'id');
        $this->addForeignKey('fk-activity-event_id', 'activity', 'event_id', 'event', 'id');
        $this->addForeignKey('fk-activity-activity_group_id', 'activity', 'activity_group_id', 'activity_group', 'id');
        $this->addForeignKey('fk-reduction-activity_group_id', 'reduction', 'activity_group_id', 'activity_group', 'id');
        $this->addForeignKey('fk-participation-activity_id', 'participation', 'activity_id', 'activity', 'id');
        $this->addForeignKey('fk-participation-participant1_id', 'participation', 'participant1_id', 'participant', 'id');
        $this->addForeignKey('fk-participation-participant2_id', 'participation', 'participant2_id', 'participant', 'id');
        $this->addForeignKey('fk-participant-activity_id', 'participant', 'activity_id', 'activity', 'id');
        $this->addForeignKey('fk-participant-couple_participant_id', 'participant', 'couple_participant_id', 'participant', 'id');
        $this->addForeignKey('fk-booking_activities-booking_id', 'booking_activities', 'booking_id', 'booking', 'id');
        $this->addForeignKey('fk-booking_activities-booking_participation_id', 'booking_activities', 'participation_id', 'participation', 'id');
    }

    public function down()
    {
        echo "m170531_145858_init_foreign_keys cannot be reverted.\n";

        return false;
    }
}
