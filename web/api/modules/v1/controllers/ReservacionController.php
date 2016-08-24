<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 2/16/2015
 * Time: 11:29 PM
 */

namespace api\modules\v1\controllers;

use backend\models\Messages;
use common\helper\SalonHelper;
use common\helper\ReservacionHelper;
use common\models\Reservacion;
use common\models\Promocion;
use common\helper\Util;
use common\models\Salon;
use yii\base\Exception;
use yii\db\Query;
use common\models\Servicio;
use common\models\SillonServicio;
use common\models\Sillon;
use yii\helpers\Url;
use Yii;

header("access-control-allow-origin: *");

class ReservacionController extends SecuredController {
    //public $modelClass = 'common\models\Reservacion';

    public function actionCancelReserv($id) {
        return true;
    }

    public function actionAbelt() {
        $date = '2015-03-13';
        return $this->getAvailableTimes(1, 1, $date);
    }

    /*
     * Chequear si hay reservaciones disponibles
     */
    public function actionCheckdate($date, $servid) {
       return  $this->getAvailableTimes($date, $servid) ? true : false;
    }

    /**
     * Este metodo se utiliza para mostrar las horas disponibles en la app
     * @param $servid
     * @param $date
     * @return array
     */
    public function actionGetavailablehours($servid, $date) {
        //return ReservacionHelper::getAvailablehours($servid, $date);
        $result = array_keys(ReservacionHelper::getAvailablehours($servid, $date));
        sort($result);
        //chequear si es hoy si es quitar las horas anteriores a la actual hora actual
        $now = time() ;
        if(date('Y-m-d', $now) == $date){
            $hour = date('Hi', $now);
            $ret = [];
            foreach($result as $h){
                if($hour < $h)
                    $ret[] = $h;

            }
            $result = $ret;
        }
        return $result;
    }

    /**
     * Esta es una de las que se utiliza para mostrar los dias ocupados en el calendario de la app
     * @param $fecha_inicio
     * @param $fecha_fin
     * @param $servid
     * @return array
     * @throws Exception
     */
    public function actionGetbusydays($fecha_inicio, $fecha_fin, $servid) {
        //return ReservacionHelper::getBusydays($fecha_inicio, $fecha_fin, $servid);
        return array_values(ReservacionHelper::getBusydays($fecha_inicio, $fecha_fin, $servid));
    }

    public function actionGettimeavailable($date, $servid) {
        $result =  $this->getAvailableTimes($date, $servid);
        return array_keys($result);
    }

    private function getAvailableTimes($date, $servid, &$duracion_serv = '') {
        $servicio = Servicio::findOne([ 'id' => $servid, 'estado' => Servicio::ESTADO_ACTIVO]);

        if (!$servicio) throw new Exception('No existe el servicio especificado');

        $duracion = $servicio->duracion;
        if (!$duracion_serv) $duracion_serv = $duracion;
        $horario_inicio = $servicio->horario_inicio;
        $horario_fin = $servicio->horario_fin;
        //ver como se obtiene con valor agregado o consulta agregada
        $cant_sillones = $servicio->getSillonServicios()->count();

        if(!$cant_sillones) throw new Exception('No existen sillones asociado al servicio especificado');

        $availableTimes = [];
        $h_inicio = date_create($horario_inicio);
        $h_fin = date_create($horario_fin);

        while ($h_inicio < $h_fin) {
            $availableTimes[date_format($h_inicio, 'Hi')] = [];
            date_add($h_inicio, date_interval_create_from_date_string($duracion . ' minutes'));
        }

        $reservaciones = SillonServicio::find()
            ->select('reservacion.id, reservacion.fecha, sillon_servicio.sillonid')
            ->innerJoin('reservacion', 'reservacion.sillon_servicioid = sillon_servicio.id')
            ->where(['sillon_servicio.servicioid' => $servid])
            ->andWhere(['>=', 'reservacion.fecha', $date . ' 00:00:00'])
            ->andWhere(['<=', 'reservacion.fecha', $date . ' 23:59:59'])
            ->asArray()->all();

        //if (!$reservaciones)
        //    return true;
        //echo '<pre>'; print_r($reservaciones); die;

        foreach($reservaciones as $reservacion) {
            $h = date_create($reservacion['fecha']);
            $h = date_format($h, 'Hi');
            $availableTimes[$h][] = $reservacion['sillonid'];

            if (count($availableTimes[$h]) == $cant_sillones)
                unset($availableTimes[$h]);
        }

        return $availableTimes;
    }

    private function getcartdetail($id) {
        $reservacion = Reservacion::find()
            ->where(['id' => $id])
            ->with('sillonServicio')
            ->with('sillonServicio.servicio')
            ->with('sillonServicio.sillon')
            ->with('sillonServicio.servicio.salon')
            ->with('sillonServicio.servicio.salon.moneda')
            ->one();

        if (!$reservacion)
            throw new Exception('La reservacion especificada no pudo ser encontrada');

        $tmp_src = '';

        if( count($imagenes = $reservacion->sillonServicio->servicio->salon->imagenes) > 0){
            $tmp_src = SalonHelper::getImgUrlFromArrayImages($reservacion->sillonServicio->servicio->salon->imagenes, false);
        }


        $new_price = null;
        if($reservacion->promocion_aplicada){
            if($reservacion->promocion_op == Promocion::SIGNO_RESTA){
                $new_price =  $reservacion->sillonServicio->servicio->precio  - $reservacion->sillonServicio->servicio->precio *  $reservacion->promocion_valor/100;
            }


        }

        return [
            "salonname" => $reservacion->sillonServicio->servicio->salon->nombre,
            "servname" => $reservacion->sillonServicio->servicio->nombre,
            "precio" => Yii::$app->formatter->asCurrency($reservacion->sillonServicio->servicio->precio, $reservacion->sillonServicio->servicio->salon->moneda->siglas),
            "nuevo_precio" => $new_price ? Yii::$app->formatter->asCurrency($new_price, $reservacion->sillonServicio->servicio->salon->moneda->siglas): false,
            "ubicacion" => $reservacion->sillonServicio->servicio->salon->ubicacion,
            "date" => $reservacion->fecha,
            "hora" => $reservacion->hora_inicio,
            "reservid" => $reservacion->id,
            "sillon" => $reservacion->sillonServicio->sillon->nombre,
            "moneda" => $reservacion->sillonServicio->servicio->salon->moneda->simbolo,
            "salonsrc" => $tmp_src,
			"servduracion" => $reservacion->sillonServicio->servicio->duracion
        ];
    }

    public function actionPrereserv($date, $servid, $promocionid = null) {
        //echo "<pre>";
        $fecha = date_create($date);
        $hora_inicio = date_format($fecha, 'Hi');
        $fecha_inicio = date_format($fecha, 'Y-m-d');
        $duracion_servicio = 0;
        //$r = $this->actionGetavailabledays($servid, $fecha_inicio, $duracion_servicio);
        $r = ReservacionHelper::getAvailablehours($servid, $fecha_inicio);
        //echo '<pre>'; print_r($r); echo 'date: ' . $date; die;
        if (isset($r[$hora_inicio]))
            $sillones = $r[$hora_inicio];
        else
            throw new Exception('Ese horario no se encuentra disponible');

        $sillon_servicio = SillonServicio::find()->where(['servicioid' => $servid])
            ->andWhere(['sillonid' => $sillones])->one();
            //->filterWhere(['not in', 'sillonid', $sillones])->one();
        //echo '<pre>'; print_r($sillon_servicio->id); die;

        //var_dump($r);
        //var_dump($sillon_servicio);

        //die;

        $reserv = new Reservacion();
        $reserv->estado = Reservacion::ESTADO_PRERESERVADO;
        $reserv->aplicacion_cliente = Reservacion::APPCLIENT_APP;
        $reserv->usuarioid = \Yii::$app->user->id;
        $reserv->sillon_servicioid = $sillon_servicio->id;
        $reserv->fecha = date_format($fecha, 'Y-m-d');
        $reserv->hora_inicio = date_format($fecha, 'Hi');
        date_add($fecha, date_interval_create_from_date_string($sillon_servicio->servicio->duracion . ' minutes'));
        $reserv->hora_fin = date_format($fecha, 'Hi');
        //$reserv->hora_fin = date_format($fecha, 'Hi');
        $reserv->salonid = $sillon_servicio->servicio->salonid;

        //Promocion
        if($promocionid){
            $promo = Promocion::find()->where(['id' => $promocionid])
                ->andWhere(['<=', 'fecha_inicio', $reserv->fecha])
                ->andWhere(['>=', 'fecha_fin', $reserv->fecha])
                ->one();
            if($promo)
            {
                $reserv->promocion_desc = $promo->descripcion;
                $reserv->promocion_aplicada =  $promo->id;
                $reserv->promocion_op = $promo->operador;
                $reserv->promocion_valor = $promo->valor;
            }

        }


        if (!$reserv->save()){
            throw new Exception(Util::modelError2String($reserv->getErrors()));
           // die;
        }

            //throw new Exception(Messages::ERROR_OP_FAIL);

        return $this->getcartdetail($reserv->id);
    }

    public function actionReserv($reservid) {
        $reservacion = Reservacion::findOne($reservid);
        if (!$reservacion)
            throw new Exception('La prereservacion no fue encontrada');

        $reservacion->estado = Reservacion::ESTADO_PENDIENTE;
        if (!$reservacion->save(false))
            throw new Exception(Messages::ERROR_OP_FAIL);
    }

    public function actionGetcartdetail($id){
        return $this->getcartdetail($id);
    }

    public function actionCancelar($id) {
        $reservacion = Reservacion::findOne($id);
        if (!$reservacion)
            return;
        if ($reservacion->estado == Reservacion::ESTADO_PENDIENTE) {
            $reservacion->estado = Reservacion::ESTADO_CANCELADA;
            if (!$reservacion->save(false))
                throw new Exception(Messages::ERROR_OP_FAIL);
        } elseif ($reservacion->estado == Reservacion::ESTADO_PRERESERVADO) {
            if (!$reservacion->delete())
                throw new Exception(Messages::ERROR_OP_FAIL);
        }
    }

    public function actionGetallreserv(){
        $reservaciones = Reservacion::find()
            ->with('sillonServicio')
            ->with('sillonServicio.servicio')
            ->with('sillonServicio.sillon')
            ->with('sillonServicio.servicio.salon')
            ->where(["usuarioid" => Yii::$app->user->id])
            //->andWhere(['<>', 'estado', Reservacion::ESTADO_CANCELADA])
            ->andWhere(['estado' => Reservacion::ESTADO_PENDIENTE])
            ->andWhere(['>=', 'reservacion.fecha', date('Y-m-d')])
            //->orderBy('fecha DESC,hora_inicio DESC')
            ->orderBy('reservacion.id DESC')
            ->all();

        $result = [];
        foreach ($reservaciones as $reservacion) {
            $imgs = $reservacion->sillonServicio->servicio->salon->imagenes;
            $img_url ="";
            if(count($imgs)>0){
                $img_url = SalonHelper::getImgUrlFromArrayImages($imgs, true);
            }

            //Nuevo precio si tiene promocion
            $new_price = '';
            if($reservacion->promocion_aplicada){
                if($reservacion->promocion_op == Promocion::SIGNO_RESTA){
                    $new_price =  $reservacion->sillonServicio->servicio->precio  - $reservacion->sillonServicio->servicio->precio *  $reservacion->promocion_valor/100;
                }

            }

            $result[] = [
                "salonname" => $reservacion->sillonServicio->servicio->salon->nombre,
                "servname"  => $reservacion->sillonServicio->servicio->nombre,
                //"precio"    => $reservacion->sillonServicio->servicio->precio,
                "precio" => Yii::$app->formatter->asCurrency($reservacion->sillonServicio->servicio->precio, $reservacion->sillonServicio->servicio->salon->moneda->siglas),
                "nuevo_precio" => $new_price ? Yii::$app->formatter->asCurrency($new_price, $reservacion->sillonServicio->servicio->salon->moneda->siglas): '',
                "ubicacion" => $reservacion->sillonServicio->servicio->salon->ubicacion,
                "date"      => $reservacion->fecha,
                "hora"      => $reservacion->hora_inicio,
                "reservid"  => $reservacion->id,
                "sillon"    => $reservacion->sillonServicio->sillon->nombre,
                "estado"    => $reservacion->estado,
                "evaluacion"=> $reservacion->evaluacion,
                "salonsrc"  => $img_url,
                //"salonsrc"  => $reservacion->sillonServicio->servicio->salon->thumbnail ? SalonHelper::getImgUrlFromArray( ["salonid"=> $reservacion->sillonServicio->servicio->salon->id ,"nombre" => $reservacion->sillonServicio->servicio->salon->thumbnail]) : null,
                "salonid"   => $reservacion->sillonServicio->servicio->salon->id,
                "salon_lng" => $reservacion->sillonServicio->servicio->salon->ubicacion_longitud,
                "salon_ltd" => $reservacion->sillonServicio->servicio->salon->ubicacion_latitud
            ];
        }
        //echo '<pre>'; print_r($result); die;
        return $result;
    }

    public function  actionPorEval(){
        $reservaciones = Reservacion::find()
            ->with('sillonServicio.servicio.salon')
            ->where(['estado' => Reservacion::ESTADO_EJECUTADA,
                     'usuarioid' => Yii::$app->user->id
                    ])
            //->andWhere(['is', 'evaluacion', null])
            //->asArray()
            ->all();

        $result = [];
        foreach($reservaciones as $r){
            $imgs = $r->sillonServicio->servicio->salon->imagenes;
            $img_url ="";
            if(count($imgs)>0){
                $img_url = SalonHelper::getImgUrlFromArrayImages($r->sillonServicio->servicio->salon->imagenes);
            }

            $result[]= [
              'reservid'  => $r->id,
              'servicio'  => $r->sillonServicio->servicio->nombre,
              'salonname' => $r->sillonServicio->servicio->salon->nombre,
              'silloname'  => $r->sillonServicio->sillon->nombre,
              'fecha'      => $r->fecha,
              'hora'      => $r->hora_inicio,
              'salonsrc'  => $img_url,
              //'salonsrc'  => $r->sillonServicio->servicio->salon->thumbnail ? SalonHelper::getImgUrlFromArray( ["salonid"=> $r->sillonServicio->servicio->salon->id ,"nombre" => $r->sillonServicio->servicio->salon->thumbnail]) : null,
              'evaluacion' => $r->evaluacion,
              'template'  => $r->evaluacion == null ? 'item' : 'item2'

            ];

            //$result[] = $tmp;
        }

        return $result;


    }


}