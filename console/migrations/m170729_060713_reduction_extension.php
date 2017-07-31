<?php

use yii\db\Migration;

class m170729_060713_reduction_extension extends Migration
{
    public function up()
    {
        $this->addColumn('reduction', 'validity_start', $this->date().' NULL');
        $this->addColumn('reduction', 'validity_end', $this->date(). ' NULL');
        $this->addColumn('reduction', 'event_id',$this->integer().' AFTER id');
    }

    public function down()
    {
        echo "m170729_060713_reduction_extension cannot be reverted.\n";

        return false;
    }
}
