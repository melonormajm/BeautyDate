<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 2/16/2015
 * Time: 11:29 PM
 */

namespace api\modules\v1\controllers;
use common\helper\SalonHelper;
use yii\helpers\Url;
use common\models\Promocion;
use common\models\Salon;
use common\models\Servicio;
use common\helper\PromocionHelper;
use yii\rest\Controller;
use Yii;

header("access-control-allow-origin: *");

//class PromocionController extends SecuredController {
class PromocionController extends Controller {
    //public $modelClass = 'common\models\Promocion';

    public function actionIndex($filter = null, $searchValue = null, $skip = null, $take= null)
    {
        $r = Promocion::find();
        if (!empty($_POST['filter']) && $_POST['filter'][2]) {
            if ($_POST['filter'][0] == 'name') {

                $r->where(['like', 'nombre', $_POST['filter'][2]]);

            }
        }
        $r = $r->with('servicio')
               ->with('servicio.salon')
                ->asArray()->all();
        return $r;
    }

    public function actionIndex1($filter = null, $searchValue = null, $skip = null, $take= null)
    {
        $r = Promocion::find()->where(['activa' => Promocion::ESTADO_ACTIVO]) //Ver con se queria que salga
             ->where(['>=', 'fecha_inicio', date('Y-m-d', time()) ])
             ->where(['>=', 'fecha_fin' , date('Y-m-d', time())] );

        if (!empty($_POST['filter']) && $_POST['filter'][2]) {
            if ($_POST['filter'][0] == 'name') {

                $r->where(['like', 'nombre', $_POST['filter'][2]]);

            }
        }
        /*$r = $r->with('servicio')
             ->with('servicio.salon')
             ->asArray()->all();
        */
        $r = $r->innerJoinWith('servicio')
               ->innerJoinWith('servicio.salon')
               ->andWhere(['servicio.estado_sistema' => Servicio::ESTADO_SISTEMA_ACTIVO, 'servicio.estado' => Servicio::ESTADO_ACTIVO])
               ->andWhere(['salon.estado_sistema' => Salon::ESTADO_SISTEMA_ACTIVADO, 'salon.estado' => Salon::ESTADO_ACTIVADO])
               ->asArray()->all();

        for ($i = 0; $i < count($r); $i++) {
            if ($r[$i]['imagen']) {
                $r[$i]['imagen']= SalonHelper::getPromocionImgUrlFromArray($r[$i], $r[$i]['servicio']['salon']['id']);
            }
        }



        return $r;
    }


    public function actionDetalle($id  = ''){

        $p = Promocion::find()->where(['id' => $id])
            ->with('servicio')
            ->with('servicio.salon')
            ->with('servicio.salon.moneda')
            ->asArray()->one();


        $p['imagen']= SalonHelper::getPromocionImgUrlFromArray($p, $p['servicio']['salon']['id']);
        $p['servicio']['nuevo_precio'] =  Yii::$app->formatter->asCurrency(PromocionHelper::obtenerNuevoPrecio($p, $p['servicio']), $p['servicio']['salon']['moneda']['siglas']);
        $p['servicio']['precio'] = Yii::$app->formatter->asCurrency($p['servicio']['precio'], $p['servicio']['salon']['moneda']['siglas']);

        return $p;


    }



}