<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 2/16/2015
 * Time: 11:29 PM
 */

namespace api\modules\v1\controllers;

use backend\models\Messages;
use backend\models\ResultException;
use common\models\LoginSocialForm;
use common\models\User;
use common\models\UserRedsocial;
use common\models\SignupForm;
use frontend\models\PasswordResetRequestForm;
use common\helper\Util;
use yii\base\Exception;
use yii\rest\Controller;
use Yii;
header("access-control-allow-origin: *");

class SiteController extends Controller {

   public function actionLogin($username, $pass)
    {
        //$user = User::findByUsername($username);
        $user = User::findByUsernameApp($username);
        if (!$user || !$user->validatePassword($pass))
            return false;
        return ["token" => $user->auth_key, "user_id" => $user->id ];
    }

    public function actionLoginsocial()
    {
        Yii::info(Yii::$app->request->post());
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $model = new LoginSocialForm();
            if ($model->load(Yii::$app->request->post(), ''))
                Yii::info('LLEGO ANTES DE LOGUEAR');
                $result = $model->login(User::TIPO_CLIENTE);
            if (!$result)
                throw new Exception(Messages::ERROR_OP_FAIL);
            else {
                $transaction->commit();
                return $result;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::info($e->getTrace());
            throw $e;
        }
    }

    public function actionLoginsocialProfile()
    {
        Yii::info(Yii::$app->request->post());
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $model = new LoginSocialForm();
            if ($model->load(Yii::$app->request->post(), ''))
                Yii::info('LLEGO ANTES DE LOGUEAR');
            $result = $model->login(User::TIPO_CLIENTE);
            if (!$result)
                throw new Exception(Messages::ERROR_OP_FAIL);
            else {
                $transaction->commit();
                return $result;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::info($e->getTrace());
            throw $e;
        }
    }




    public function actionChecksocial($rs, $rsid){
        $userSocial = UserRedsocial::findOne([
            'red_social_id' => $rs,
            'user_red_social_id' => $rsid]
        );
        if(!$userSocial)
            return ["registered" => false ];
        else
            return ["registered" => true ];

    }

    public function actionTest(){
        return ["result" => true];
    }


    public function actionSignup(){
        $model = new SignupForm();
        if($model->load(Yii::$app->request->post())){
            $model->user_type = User::TIPO_CLIENTE;
            $model->auth_type = User::AUTH_LOCAL;

            if($user = $model->signup()){
                return ["token" => $user->auth_key, "user_id" => $user->id ];
            }
        }
        if(count($model->getErrors())){
            throw new Exception(Util::modelError2String($model->getErrors()));
        }
        throw new Exception(Messages::ERROR_OP_FAIL);
    }

    public function actionUserinfo($token){
        //$user = User::findIdentityByAccessToken($token);
        $userret = User::find()->with('userRedsocials')
            ->where(['auth_key'=>$token])->asArray()->one();

        return $userret;
    }

    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        $model->user_type = User::TIPO_CLIENTE;

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->validate())
                throw new Exception(Util::modelError2String($model->getErrors()));
                //throw new Exception(json_encode($model->getErrors()));
            if (!$model->sendEmail())
                throw new Exception(Messages::ERROR_OP_FAIL);

            return true;
        }
        return false;
    }

    public function actionRegistersocial()
    {
        $user = null;
        if ($sn = Yii::$app->request->post('red') && $nid = Yii::$app->request->post('nid')) {
            $red_social = UserRedsocial::find()->where(['red_social_id' => Yii::$app->request->post('red'), 'user_red_social_id' => Yii::$app->request->post('nid')])->one();
            if (!$red_social) {
                $red_social = new UserRedsocial();
                $red_social->red_social_id = Yii::$app->request->post('red');
                $red_social->user_red_social_id = Yii::$app->request->post('nid');
            }
            //usuario ya esta registrado solo tenemos que actualizar sus datos y el token
            $red_social->nombre = Yii::$app->request->post("firstname");
            $red_social->apellido = Yii::$app->request->post("lastname");
            $red_social->email = Yii::$app->request->post("email");
            $red_social->link = Yii::$app->request->post("link");


            //Hay que crear el usuario
            $user = !$red_social->usuarioid ? new User(): $red_social->usuario;

            $user->username = $red_social->nombre;
            $user->email = $red_social->email;
            $user->auth_type = "LOCAL";
            $user->last_red_social = Yii::$app->request->post("red");
            $user->auth_key = Yii::$app->request->post("token");
            $user->setPassword("passtest");
            $user->generateAuthKey();
            $user->save(false);
            $red_social->usuarioid = $user->id;
            $red_social->save();
            return $user->auth_key;
        }
    }

    public function actionPaypalNotification() {
        Yii::info($_REQUEST, 'paypal');
    }

    function actionPasswordrec($email){
        return "OK";
    }


}