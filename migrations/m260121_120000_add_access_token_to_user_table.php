<?php

use yii\db\Migration;

/**
 * Class m260121_120000_add_access_token_to_user_table
 */
class m260121_120000_add_access_token_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'access_token', $this->string()->unique()->after('auth_key'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'access_token');
    }
}
