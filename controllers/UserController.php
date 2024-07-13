<?php

namespace app\controllers;

use app\models\User;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\db\Exception;
use yii\rest\ActiveController;
use yii\rest\Controller;

class UserController extends Controller
{
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

    /**
     * @return array
     * @throws Exception
     */
    public function actionLogin()
    {
        /** @var Jwt $jwt */
        if(Yii::$app->user->getIdentity())
            return $this->asJson(Yii::$app->user->getIdentity());

        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key = $jwt->getKey();
        $time = time();

        $user = new User();
        if ($user->save(false)) {
            $token = $jwt->getBuilder()
                ->issuedBy('http://example.com')// Configures the issuer (iss claim)
                ->permittedFor('http://example.org')// Configures the audience (aud claim)
                ->identifiedBy('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
                ->issuedAt($time)
                ->expiresAt($time + 31536000)
                ->withClaim('id', $user->getId())
                ->getToken($signer, $key);
            $user->access_token = (string) $token;
            $user->update();
            return ['token' => (string) $token];
        }
        return ['success' => false];
    }

    /**
     * @return \yii\web\Response
     */
    public function actionData()
    {
        return $this->asJson([
            'success' => true,
        ]);
    }
}