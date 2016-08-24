<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 2/16/2015
 * Time: 11:29 PM
 */

namespace backend\controllers;

use yii\rest\Controller;

class AuthenticationController extends Controller {

    /**
     * @param $username
     * @param $password
     * @param $mode 0:sitio, 1:facebook
     * @return bool
     */
    public function actionAuthenticate($username, $password, $mode) {
        return true;
    }

    public function actionTesturl() {
        return true;
    }
}