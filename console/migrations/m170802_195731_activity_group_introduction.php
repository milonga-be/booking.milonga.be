<?php

use yii\db\Migration;

class m170802_195731_activity_group_introduction extends Migration
{
    public function up()
    {
        $this->addColumn('activity_group', 'introduction', $this->string(500));
    }

    public function down()
    {
        $this->dropColumn('activity_group', 'introduction');
    }
}
