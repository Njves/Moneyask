<?php

namespace app\service;
use app\models\Task;
use Exception;
use Google\Client;
use Google\Service\Drive;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Kunnu\Dropbox\Models\AccessToken;
use Psy\Util\Json;
use Yii;

class SyncFileService
{
    private Dropbox $dropbox;

    public function __construct()
    {
        $app = new DropboxApp("zbwvuivq5s15ml5", "bk8h60gujbts7ld");
        $refresh = 'Ow0L2yZomqIAAAAAAAAAAV94F7yvj2mkf0gKuIiWUNySI6bz1gvqs4V7DE2hxUOJ';
        $this->dropbox = new Dropbox($app);
        $authHelper = $this->dropbox->getAuthHelper();
        $token = $authHelper->getRefreshedAccessToken(new AccessToken(['refresh_token' => $refresh]));
        $this->dropbox->setAccessToken($token->getToken());
    }

    /**
     * @throws DropboxClientException
     */
    public function uploadTasks($tasks)
    {

        $result = [];
        foreach ($tasks as $task) {
            /** @var Task $task */
            $result[] = $this->dropbox->upload(new DropboxFile(Yii::getAlias('@app') . '/image.jpg'), '/image.jpg', ['autorename' => true]);
        }
        return $result;
    }
}