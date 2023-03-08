<?php

use yii\db\Migration;

/**
 * Class m230306_155433_booking_source
 */
class m230306_155433_booking_source extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('booking', 'source', $this->string(32).' DEFAULT "website"');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('booking', 'source');
    }
}
