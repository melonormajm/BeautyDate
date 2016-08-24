<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 8/30/2015
 * Time: 1:33 PM
 */
namespace api\modules\v1\controllers;
use common\models\SignupForm;
use common\models\User;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\Url;
use Yii;

header("access-control-allow-origin: *");

class UsuarioController extends SecuredController {



    public function actionPerfilUpdate(){

        $signForm = new SignupForm();
        if ($signForm->load(Yii::$app->request->post()) && $signForm->updateProfile(Yii::$app->user->id)) {
            return ['result'=> true];
        }

        return ['result'=> false];
    }

    public function actionPerfilLoad(){
        return User::find()->where(['id' =>Yii::$app->user->id])->one();
    }


}