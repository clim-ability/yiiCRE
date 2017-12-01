<?php

use yii\db\Migration;

class m171130_160843_add_admin_user extends Migration
{
    public function safeUp()
    {
        $this->insert('user', [
            'username' => LOCAL_CONFIG_DB_USER,
            'email' => CRE_ADMIN_EMAIL,
            'password_hash' => LOCAL_CONFIG_DB_PASSWORD   
        ]);
    }

    public function safeDown()
    {
        $this->delete('user', ['username' => LOCAL_CONFIG_DB_USER]);
    }
    
}

