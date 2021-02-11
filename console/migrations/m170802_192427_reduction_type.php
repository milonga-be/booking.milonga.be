<?php

use yii\db\Migration;

class m170802_192427_reduction_type extends Migration
{
    public function up()
    {
        $this->addColumn('reduction', 'type', $this->string('30'));
        $this->renameColumn('reduction', 'percentage', 'value');
    }

    public function down()
    {
        $this->dropColumn('reduction', 'type');
        $this->renameColumn('reduction', 'value', 'percentage');
    }
}
