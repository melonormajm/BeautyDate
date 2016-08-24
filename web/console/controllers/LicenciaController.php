<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 8/26/2015
 * Time: 8:14 PM
 */
namespace console\controllers;

use common\helper\SalonHelper;
use common\models\Salon;
use common\models\Servicio;
use common\models\Sillon;
use yii\console\Controller;
use common\models\Licencia;
use Yii;


class LicenciaController extends Controller{


    function actionCheck(){

        Yii::info('Schedule verificacion de plazo de licencias');

        Yii::$app->db->createCommand("UPDATE licencia SET estado=:newstate, detalles=:detail WHERE estado=:oldstate and fecha_fin is not NULL and fecha_fin < :now;")
            ->bindValue(':newstate', Licencia::ESTADO_INACTIVO)
            ->bindValue(':oldstate', Licencia::ESTADO_ACTIVO)
            ->bindValue(':detail', 'Licencia expiró')
            ->bindValue(':now', date('Y-m-d', time()))
            ->execute();

        //Actualizando el estado de los salones
        Yii::$app->db->createCommand("UPDATE salon SET estado_sistema=:newstate WHERE estado_sistema=:oldstate and licenciaid in (select id from licencia WHERE estado=:licinactiva);")
            ->bindValue(':newstate', Salon::ESTADO_SISTEMA_INACTIVO)
            ->bindValue(':oldstate', Salon::ESTADO_SISTEMA_ACTIVADO)
            ->bindValue(':licinactiva', Licencia::ESTADO_INACTIVO)
            ->execute();

        return 0;
    }

    function actionTest1()
    {

        $salonid = 2;
        $servicios = Servicio::find()->where(['salonid' => $salonid])
            ->with('sillonServicios')
            ->with('sillonServicios.sillon')
            ->asArray()
            ->all();

        //echo '<pre>';
        //print_r($servicios);
        //die;

        foreach ($servicios as $s) {
            if ($s->estado_sitema == Servicio::ESTADO_SISTEMA_INACTIVO) {
                if (isset($s->sillonServicios)) {
                    foreach ($s->sillonServicios as $ss) {
                        if ($ss->sillon == Sillon::ESTADO_ACTIVO) {
                            $s->estado_sistema = Servicio::ESTADO_SISTEMA_ACTIVO;
                            $s->save();
                            break;
                        }
                    }
                }
            } else {
                if (isset($s->sillonServicios)) {
                    foreach ($s->sillonServicios as $ss) {
                        if ($ss->sillon == Sillon::ESTADO_ACTIVO) {
                            $s->estado_sistema = Servicio::ESTADO_SISTEMA_ACTIVO;
                            $s->save();
                            break;
                        }
                    }
                }


            }
        }


    }


    function actionTest()
    {
    SalonHelper::checkServiciosEstados(6);
    }







    }