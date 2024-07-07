<?php

namespace app\controllers;

use yii\rest\ActiveController;

class TaskController extends ActiveController
{
    public  $modelClass = 'app\models\Task';
}