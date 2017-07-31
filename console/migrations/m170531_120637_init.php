<?php

use yii\db\Migration;

class m170531_120637_init extends Migration
{
    public function up()
    {
        $this->createTable('event', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'title' => $this->string(),
        ]);

        $this->createTable('activity_group', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer(),
            'title' => $this->string(500),
        ]);

        $this->createTable('activity', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer(),
            'activity_group_id' => $this->integer(),
            'title' => $this->string(500),
            'couple_activity' => $this->boolean(),
            'price' => $this->money(),
        ]);

        $this->createTable('reduction', [
            'id' => $this->primaryKey(),
            'activity_group_id' => $this->integer(),
            'lower_limit' => $this->integer(),
            'higher_limit' => $this->boolean(),
            'percentage' => $this->decimal(),
        ]);

        $this->createTable('participation', [
            'id' => $this->primaryKey(),
            'activity_id' => $this->integer(),
            'participant1_id' => $this->integer(),
            'participant2_id' => $this->integer(),
        ]);

        $this->createTable('participant', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string(250),
            'lastname' => $this->string(250),
            'email' => $this->string(250),
            'activity_id' => $this->integer(),
            'couple_participant_id' => $this->integer(),
        ]);

        $this->createTable('booking', [
            'id' => $this->primaryKey(),
            'total_price' => $this->money(),
        ]);

        $this->createTable('booking_participations', [
            'booking_id' => $this->integer(),
            'participation_id' => $this->integer(),
            'PRIMARY KEY (booking_id,participation_id)'
        ]);
    }

    public function down()
    {
        echo "m170531_120637_init cannot be reverted.\n";

        return false;
    }
}
