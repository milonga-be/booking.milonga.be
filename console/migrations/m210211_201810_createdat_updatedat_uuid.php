<?php

use yii\db\Migration;

class m210211_201810_createdat_updatedat_uuid extends Migration
{
    public function up()
    {
        $this->addColumn('activity', 'created_at', $this->datetime().' DEFAULT NULL AFTER id');
        $this->addColumn('activity', 'updated_at', $this->datetime().' DEFAULT NULL AFTER created_at');
        $this->addColumn('activity', 'uuid',  $this->string(36).' AFTER updated_at');

        $this->addColumn('activity_group', 'created_at', $this->datetime().' DEFAULT NULL AFTER id');
        $this->addColumn('activity_group', 'updated_at', $this->datetime().' DEFAULT NULL AFTER created_at');
        $this->addColumn('activity_group', 'uuid',  $this->string(36).' AFTER updated_at');

        $this->addColumn('booking', 'created_at', $this->datetime().' DEFAULT NULL AFTER id');
        $this->addColumn('booking', 'updated_at', $this->datetime().' DEFAULT NULL AFTER created_at');
        // $this->addColumn('booking', 'uuid',  $this->string(36).' AFTER updated_at');

        $this->addColumn('event', 'created_at', $this->datetime().' DEFAULT NULL AFTER id');
        $this->addColumn('event', 'updated_at', $this->datetime().' DEFAULT NULL AFTER created_at');
        $this->addColumn('event', 'uuid',  $this->string(36).' AFTER updated_at');

        $this->addColumn('participant', 'created_at', $this->datetime().' DEFAULT NULL AFTER id');
        $this->addColumn('participant', 'updated_at', $this->datetime().' DEFAULT NULL AFTER created_at');
        $this->addColumn('participant', 'uuid',  $this->string(36).' AFTER updated_at');

        $this->addColumn('participation', 'created_at', $this->datetime().' DEFAULT NULL AFTER id');
        $this->addColumn('participation', 'updated_at', $this->datetime().' DEFAULT NULL AFTER created_at');
        $this->addColumn('participation', 'uuid',  $this->string(36).' AFTER updated_at');

        $this->addColumn('reduction', 'created_at', $this->datetime().' DEFAULT NULL AFTER id');
        $this->addColumn('reduction', 'updated_at', $this->datetime().' DEFAULT NULL AFTER created_at');
        $this->addColumn('reduction', 'uuid',  $this->string(36).' AFTER updated_at');
    }

    public function down()
    {
        echo "m210211_201810_createdat_updatedat_uuid cannot be reverted.\n";

        return false;
    }
}
