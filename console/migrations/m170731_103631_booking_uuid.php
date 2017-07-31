<?php

use yii\db\Migration;

class m170731_103631_booking_uuid extends Migration
{
    public function up()
    {
        $this->addColumn('booking', 'uuid', $this->string(32));
    }

    public function down()
    {
        $this->dropColumn('booking', 'uuid');
    }
}
