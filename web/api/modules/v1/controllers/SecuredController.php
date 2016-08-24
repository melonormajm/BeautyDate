<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 4/5/2015
 * Time: 10:45 PM
 */

namespace api\modules\v1\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;

class SecuredController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
            'tokenParam' => 'token',
        ];
        return $behaviors;
    }
}