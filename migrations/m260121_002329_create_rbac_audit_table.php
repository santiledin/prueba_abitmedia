<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rbac_audit}}`.
 */
class m260121_002329_create_rbac_audit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rbac_audit}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->comment('User ID who was assigned/revoked'),
            'action' => $this->string(50)->notNull()->comment('ASSIGN or REVOKE'),
            'item_name' => $this->string(64)->notNull()->comment('Role or Permission name'),
            'changed_by' => $this->integer()->notNull()->comment('User ID who performed the action'),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-rbac_audit-user_id}}',
            '{{%rbac_audit}}',
            'user_id'
        );

        $this->createIndex(
            '{{%idx-rbac_audit-changed_by}}',
            '{{%rbac_audit}}',
            'changed_by'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rbac_audit}}');
    }
}
