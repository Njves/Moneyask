<?php

namespace app\controllers;

use app\models\User;
use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
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
     * @return \yii\web\Response
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

        $token = $jwt->getBuilder()
            ->issuedBy('http://example.com')// Configures the issuer (iss claim)
            ->permittedFor('http://example.org')// Configures the audience (aud claim)
            ->identifiedBy('4f1g23a12aa', true)// Configures the id (jti claim), replicating as a header item
            ->issuedAt($time)// Configures the time that the token was issue (iat claim)
            ->expiresAt($time + 31536000)// Configures the expiration time of the token (exp claim)
            ->withClaim('id', 100)// Configures a new claim, called "uid"
            ->getToken($signer, $key); // Retrieves the generated token
        $user = new User();
        $user->access_token = $token;
        $user->save();
        return $this->asJson([
            'token' => (string)$token,
        ]);
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