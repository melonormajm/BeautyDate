<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 2/16/2015
 * Time: 11:29 PM
 */

namespace api\modules\v1\controllers;

use common\models\Categoria;
use yii\helpers\Url;
use yii\rest\Controller;
use Yii;

header("access-control-allow-origin: *");

//class CategoriaController extends SecuredController {
class CategoriaController extends Controller {
    //public $modelClass = 'common\models\Categoria';



    public function actionIndex(){
        $r = Categoria::find()->orderBy('orden ASC')->all();
        for( $i = 0; $i<count($r); $i++){
            $r[$i]['portada_img'] = $r[$i]->getImageurl( 'portada',  false);
            $r[$i]['thumbnail'] = $r[$i]->getImageurl( 'principal',  false);
        }


        Yii::info($r);
        return $r;
    }
	
	public function actionView($id){
		return Categoria::findOne($id);
	}
}