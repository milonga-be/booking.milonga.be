<?php

use yii\db\Migration;

class m170601_121150_additional_fields extends Migration
{
    public function up()
    {
        // Adding a description for the activities
        $this->addColumn('activity', 'description', $this->string(500));
        // Adding a date and a time for the activites
        $this->addColumn('activity', 'datetime', $this->datetime());
        // Adding a phone for a participant
        $this->addColumn('participant', 'phone', $this->string(50));
    }

    public function down()
    {
        echo "m170601_121150_additional_fields cannot be reverted.\n";

        return false;
    }
}
