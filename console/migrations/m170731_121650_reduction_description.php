<?php

use yii\db\Migration;

class m170731_121650_reduction_description extends Migration
{
    public function up()
    {
        $this->addColumn('reduction', 'description', $this->string(500));
    }

    public function down()
    {
        $this->dropColumn('reduction', 'description');
    }
}
