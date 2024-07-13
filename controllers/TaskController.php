<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use app\service\SyncFileService;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\rest\ActiveController;

class TaskController extends ActiveController
{
    public  $modelClass = 'app\models\Task';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'login',
            ],
        ];

        return $behaviors;
    }

    public function actionSync()
    {
        Yii::$container->get(SyncFileService::class);
    }
}