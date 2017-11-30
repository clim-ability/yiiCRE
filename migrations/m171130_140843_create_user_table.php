<?php

use yii\db\Schema;
use yii\db\Migration;

class m171130_140843_create_user_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('user', [
            'id'                   => Schema::TYPE_PK,
            'username'             => Schema::TYPE_STRING . '(25) NOT NULL',
            'email'                => Schema::TYPE_STRING . '(255) NOT NULL',
            'password_hash'        => Schema::TYPE_STRING . '(60) NOT NULL',
            'created_at'           => 'timestamp with time zone NOT NULL DEFAULT now()',
            'updated_at'           => 'timestamp with time zone NOT NULL DEFAULT now()',
        ], $this->tableOptions);

        $this->createIndex('user_unique_username', 'user', 'username', true);
        $this->createIndex('user_unique_email', 'user', 'email', true);
    }

    public function safeDown()
    {
        $this->dropTable('user');
    }
}