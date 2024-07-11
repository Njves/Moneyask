<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m240707_115215_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string(),
            'priority' => $this->integer(),
            'done_at' => $this->dateTime(),
            'parent_id' => $this->integer()->null(),
            'user_id' => $this->integer(),
        ]);
//        $this->addForeignKey('fk_task_user', 'task', 'user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task}}');
    }
}
