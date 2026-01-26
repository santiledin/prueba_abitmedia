<?php

use yii\db\Migration;

/**
 * Migración para crear las tablas RBAC requeridas por yii\rbac\DbManager.
 * Estas tablas son necesarias para el sistema de control de acceso basado en roles.
 */
class m260121_000000_create_rbac_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // Tabla auth_rule: Reglas de negocio para permisos
        $this->createTable('{{%auth_rule}}', [
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
        ], $tableOptions);

        // Tabla auth_item: Roles y permisos
        $this->createTable('{{%auth_item}}', [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-auth_item-rule_name}}',
            '{{%auth_item}}',
            'rule_name',
            '{{%auth_rule}}',
            'name',
            'SET NULL',
            'CASCADE'
        );

        $this->createIndex('{{%idx-auth_item-type}}', '{{%auth_item}}', 'type');

        // Tabla auth_item_child: Jerarquía de roles (herencia)
        $this->createTable('{{%auth_item_child}}', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY ([[parent]], [[child]])',
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-auth_item_child-parent}}',
            '{{%auth_item_child}}',
            'parent',
            '{{%auth_item}}',
            'name',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-auth_item_child-child}}',
            '{{%auth_item_child}}',
            'child',
            '{{%auth_item}}',
            'name',
            'CASCADE',
            'CASCADE'
        );

        // Tabla auth_assignment: Asignación de roles a usuarios
        $this->createTable('{{%auth_assignment}}', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY ([[item_name]], [[user_id]])',
        ], $tableOptions);

        $this->addForeignKey(
            '{{%fk-auth_assignment-item_name}}',
            '{{%auth_assignment}}',
            'item_name',
            '{{%auth_item}}',
            'name',
            'CASCADE',
            'CASCADE'
        );

        // Nota: No agregamos FK a user porque el ID puede ser string o int según la app
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth_assignment}}');
        $this->dropTable('{{%auth_item_child}}');
        $this->dropTable('{{%auth_item}}');
        $this->dropTable('{{%auth_rule}}');
    }
}
