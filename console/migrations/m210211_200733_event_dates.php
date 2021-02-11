<?php

use yii\db\Migration;

class m210211_200733_event_dates extends Migration
{
    public function up()
    {
        $this->addColumn('event', 'start_date', $this->date());
        $this->addColumn('event', 'end_date', $this->date());
    }

    public function down()
    {
        $this->dropColumn('event', 'start_date');
        $this->dropColumn('event', 'end_date');
    }
}
