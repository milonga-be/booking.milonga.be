<?php

use yii\db\Migration;

class m170731_111755_booking_personal_info extends Migration
{
    public function up()
    {
        $this->addColumn('booking', 'firstname', $this->string(500));
        $this->addColumn('booking', 'lastname', $this->string(500));
        $this->addColumn('booking', 'email', $this->string(500));
        $this->addColumn('booking', 'phone', $this->string(500));
    }

    public function down()
    {
        $this->dropColumn('booking', 'firstname');
        $this->dropColumn('booking', 'lastname');
        $this->dropColumn('booking', 'email');
        $this->dropColumn('booking', 'phone');
    }
}
