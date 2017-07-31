<?php

use yii\db\Migration;

class m170731_141513_activity_group_description extends Migration
{
    public function up()
    {
        $this->addColumn('activity_group', 'description', $this->string(500));
    }

    public function down()
    {
        $this->dropColumn('activity_group', 'description');
    }
}
