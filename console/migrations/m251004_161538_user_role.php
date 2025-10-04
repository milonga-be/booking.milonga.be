<?php

use yii\db\Migration;

/**
 * Class m251004_161538_user_role
 */
class m251004_161538_user_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'role', $this->string()->defaultValue('administrator'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'role');
    }
}
