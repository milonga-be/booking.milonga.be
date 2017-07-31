<?php

use yii\db\Migration;

class m170729_065514_reduction_pct_correction extends Migration
{
    public function up()
    {
        $this->alterColumn('reduction', 'percentage', $this->decimal(5,2));
    }

    public function down()
    {
        echo "m170729_065514_reduction_pct_correction cannot be reverted.\n";

        return false;
    }
}
