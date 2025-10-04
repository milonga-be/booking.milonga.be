<?php

use yii\db\Migration;

/**
 * Class m250917_155230_user_uuid
 */
class m250917_155230_user_uuid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'uuid', $this->string(32)->null()->after('id'));

        // Populate UUID for existing users
        if ($this->db->driverName === 'mysql') {
            $this->execute('UPDATE {{%user}} SET `uuid` = REPLACE(UUID(), "-", "")');
        }

        // Make the column not nullable and unique after populating
        $this->alterColumn('{{%user}}', 'uuid', $this->string(32)->notNull()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'uuid');
    }
}
