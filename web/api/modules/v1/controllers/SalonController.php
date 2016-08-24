<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 2/16/2015
 * Time: 11:29 PM
 */

namespace api\modules\v1\controllers;

use backend\models\Messages;
use common\models\Categoria;
use common\models\ClienteSalonFavorito;
use common\models\Reservacion;
use common\models\Salon;
use common\models\Servicio;
use common\helper\SalonHelper;
use common\helper\CategoriaHelper;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\Url;
use yii\rest\Controller;
use Yii;

header("access-control-allow-origin: *");

//class SalonController extends SecuredController {
class SalonController extends Controller {
    //public $modelClass = 'common\models\Salon';

    var $cateid = "";

    public function actionView($id){
        $r = Salon::find()
            ->with(['servicios' =>  function ($query) {
                $query->andWhere(['servicio.estado_sistema' => Servicio::ESTADO_SISTEMA_ACTIVO,
                                  'servicio.estado' => Servicio::ESTADO_ACTIVO]);
            }])
            ->with('servicios.categoria')
            ->with('imagenes')
            /*->with(['imagenes' => function($query){
                $query->andWhere([ 'imagenes.principal' => null ]);
            }])*/
            ->with('moneda')
            ->with(['clienteSalonFavoritos' => function ($query) {
                $query->andWhere(['usuarioid' => Yii::$app->user->id]);
            }])
            ->where(['salon.id' => $id, 'salon.estado_sistema' => Salon::ESTADO_SISTEMA_ACTIVADO, 'salon.estado' => Salon::ESTADO_ACTIVADO])
            //->andWhere(['servicio.estado_sistema' => Servicio::ESTADO_SISTEMA_ACTIVO, 'servicio.estado' => Servicio::ESTADO_ACTIVO])
            ->asArray()
            ->one();

        if($r){
            if($r['imagenes']){
                for( $j = 0; $j<count($r['imagenes']); $j++){
                   //$tmp = Url::to('@web/' . $r['imagenes'][$j]['url'], true);
                   //$r['imagenes'][$j]['url'] = str_replace("api.beautydate", "beautydate", $tmp);
                    $r['imagenes'][$j]['url'] = SalonHelper::getImgUrlFromArray($r['imagenes'][$j]);
                }
            }
            if(!empty($r['moneda'])){
                foreach( $r['servicios'] as &$servicio ){
                    $precio = Yii::$app->formatter->asCurrency($servicio['precio'], $r['moneda']['siglas']);
                    $servicio['precio'] = $precio;

                    //if($servicio['imagen'])
                    //    $servicio['imagen'] = str_replace("api.beautydate", "beautydate",Url::to('@web/' . $servicio['imagen'], true));

                    if($servicio['categoria'])
                        $servicio['categoria']['thumbnail'] = CategoriaHelper::getImgUrlFromArray($servicio['categoria'], 'principal',  false);
                        $servicio['categoria']['servicio_img'] = CategoriaHelper::getImgUrlFromArray($servicio['categoria'], 'servicio',  false);
                }
            }

        }
       return $r;
    }

    public function actionIndex1($filter = null, $searchValue = null, $skip = null, $take= null)
    {
        $r = Salon::find()->where(['salon.estado_sistema' => Salon::ESTADO_SISTEMA_ACTIVADO, 'salon.estado' => Salon::ESTADO_ACTIVADO]);
        $r->innerJoinWith('servicios');
        $r->innerJoinWith('servicios.categoria');

        $r->andWhere(['servicio.estado_sistema' => Servicio::ESTADO_SISTEMA_ACTIVO, 'servicio.estado' => Servicio::ESTADO_ACTIVO]);

        $fav = false;
        if (!empty($_POST['filter']) && $_POST['filter'][2]) {
            if ($_POST['filter'][0] == 'query') {

                $r->andWhere( ['like', 'LOWER(salon.nombre)',  $_POST['filter'][2] ]);

            }if ($_POST['filter'][0] == 'categid') {
                $this->cateid = $_POST['filter'][2];
                //$this->cateid = '1';
                /*$r->innerJoinWith(['categoriaSalons' => function ($query) {
                    $query->andWhere(['categoriaid' => $this->cateid]);
                }]);*/

                //$r->innerJoinWith('servicios.categoria');
                $r->andWhere(['categoria.id' => $this->cateid]);

            }if ($_POST['filter'][0] == 'favorite') {
                $r->innerJoinWith(['clienteSalonFavoritos' => function ($query) {
                    $query->andWhere(['cliente_salon_favorito.usuarioid' => Yii::$app->user->id]);
                }]);
                $fav = true;
            }

        }
          //$r->with('servicios')
            $r->with('imagenes');
           // ->with('categoriaSalons.categoria');
            if(!$fav)
                $r->with(['clienteSalonFavoritos' => function ($query) {
                  $query->andWhere(['usuarioid' => Yii::$app->user->id]);
              }]);


         //$r->with('categorias');
         $r->orderBy('evaluacion DESC');
        $r = $r ->asArray()->all();
        //$r = $r -> all();

        for ($i = 0; $i < count($r); $i++) {

            //$r[$i]['thumbnail'] = !empty($r[$i]['thumbnail']) ? SalonHelper::getImgUrlFromArray( ["salonid"=>$r[$i]['id'] ,"nombre" => $r[$i]['thumbnail']]) : null;
            if ($r[$i]['imagenes']) {
                for ($j = 0; $j < count($r[$i]['imagenes']); $j++) {
                    //$tmp = Url::to('@web/' . $r[$i]['imagenes'][$j]['url'], true);
                    //$r[$i]['imagenes'][$j]['url'] = str_replace("api.beautydate", "beautydate", $tmp);
                    //$r[$i]['imagenes'][$j]['url'] = SalonHelper::getImgUrlFromArray($r[$i]['imagenes'][$j]);
                    $r[$i]['imagenes'][$j]['url'] = SalonHelper::getImgUrlFromArray($r[$i]['imagenes'][$j], SalonHelper::existImageThumbFromArr($r[$i]['imagenes'][$j]));
                }
            }

            //Imagenes de las categorias de los servicios
            if($r[$i]['servicios']){
                for ($j = 0; $j < count($r[$i]['servicios']); $j++) {
                    // $r[$i]['categoriaSalons'][$j]['categoria']['thumbnail'] = str_replace("api", "backend",Url::to('@web/images/categoria/' . $r[$i]['categoriaSalons'][$j]['categoria']['thumbnail'], true));
                    if($r[$i]['servicios'][$j]['categoria'] != null){
                        //$r[$i]['servicios'][$j]['categoria']['thumbnail'] = str_replace("api.beautydate", "beautydate.backend", Url::to('@web/images/categoria/' . $r[$i]['servicios'][$j]['categoria']['id'] . '.' . $r[$i]['servicios'][$j]['categoria']['thumbnail'], true));
                        $r[$i]['servicios'][$j]['categoria']['thumbnail'] =  CategoriaHelper::getImgUrlFromArray($r[$i]['servicios'][$j]['categoria'], 'principal',  false);
                    }

                }

            }


        }
        return $r;
    }

   //TODO mostrar las promociones junto con los servicios
   public function actionGetSalones($nombre)
    {
        $r = Salon::find()
            ->where(['nombre' => $nombre])
            ->with('servicios')->asArray()
            ->all();
        return $r;
    }


    public function actionTestsite()
    {
        return true;
    }

    public function actionTestarr(){

        return $_REQUEST['filter'];
    }

    public function actionAddfavorito($salonid) {
        $s = Salon::findOne($salonid);
        if (!$s)
            throw new Exception('No existe el salon especificado');

        return $s->link('usuarios', Yii::$app->user->identity);
    }

    public function actionRemovefavorito($salonid) {
        $cliente_salon = ClienteSalonFavorito::findOne([
            "usuarioid" => Yii::$app->user->id,
            "salonid" => $salonid
        ]);
        if (!$cliente_salon)
            throw new Exception('No existe el salon especificado');
        if (!$cliente_salon->delete())
            throw new Exception(Messages::ERROR_OP_FAIL);
    }

    public function  actionEvaluar($reservid, $eval='') {
        $reservacion = Reservacion::find()->innerJoinWith('sillonServicio')
            ->innerJoinWith('sillonServicio.servicio')
            ->innerJoinWith('sillonServicio.servicio.salon')
            ->where(['reservacion.id' => $reservid])
            ->andWhere(['reservacion.estado' => Reservacion::ESTADO_EJECUTADA])
            //->andWhere('reservacion.evaluacion IS NULL')
            ->one();
        if (!$reservacion)
            throw new Exception('No existe la reservacion especificada o no se puede realizar la operacion');

        $reservacion->evaluacion = $eval;
        if (!$reservacion->save(false))
            throw new Exception(Messages::ERROR_OP_FAIL);

        //actualizar el promedio de evaluacion del salon
        $evaluaciones = (new Query())->select('SUM(r.evaluacion) as total, COUNT(r.evaluacion) as cant')
            ->from('reservacion r')
            ->innerJoin('sillon_servicio ss', 'ss.id=r.sillon_servicioid')
            ->innerJoin('servicio serv', 'ss.servicioid=serv.id')
            ->innerJoin('salon s', 'serv.salonid=s.id')
            ->where('r.evaluacion IS NOT NULL')
            ->andWhere([
                's.id' => $reservacion->sillonServicio->servicio->salonid
            ])->all();

        $total = $evaluaciones[0]['total'];
        $cant = $evaluaciones[0]['cant'];

        $prom = $total/$cant;

        $salon = $reservacion->sillonServicio->servicio->salon;

        $salon->evaluacion = $prom;

        if (!$salon->save(false))
            throw new Exception(Messages::ERROR_OP_FAIL);
    }


    public function actionViewmap($id){
        $r = Salon::find()->where(['id' => $id])->one();
        return $r;



    }

}