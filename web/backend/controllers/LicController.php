<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 2/16/2015
 * Time: 11:29 PM
 */

namespace backend\controllers;

use backend\models\Usuario;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

class LicController extends ActiveController {

    public $modelClass = 'backend\models\Licencia';

    public function actionMio()
    {
        return new ActiveDataProvider([
            'query' => Usuario::find(),
        ]);
    }
}