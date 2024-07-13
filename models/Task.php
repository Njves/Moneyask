<?php

namespace app\models;

use Exception;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string|null $description
 * @property int|null $priority
 * @property string|null $done_at
 * @property int|null $parent_id
 * @property int|null $user_id
 * @property Task $parent
 * @property Task[] $subtasks
 */
class Task extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['priority', 'parent_id'], 'integer'],
            [['done_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->user_id = Yii::$app->user->id;
            }
            return true;
        }
        return false;
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function afterSave($insert, $changedAttributes): bool
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->parent_id == $this->id) {
            $this->delete();
            throw new Exception('Невозможно создать задачу с ссылкой на себя в качестве родителя');
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'priority' => 'Priority',
            'done_at' => 'Done At',
            'parent_id' => 'Parent ID',
            'user_id' => "User ID"
        ];
    }

    public function getParent(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'parent_id']);
    }

    public function getSubtasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['parent_id' => 'id']);
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['subtasks'] = 'subtasks';
        return $fields;
    }


}
