<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 8/17/2015
 * Time: 12:20 PM
 */

namespace common\helper;

use common\models\IpnNotification;
use common\models\Salon;
use common\models\LicenciaSpec;
use common\models\Licencia;
use yii\base\Exception;
use Yii;

class LicenciaHelper
{
    static $user_id;

    public static function saveLicencia($data, $ipnrecord, $tipo_licencia_id) {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            self::$user_id = $ipnrecord->user_id;
            $salon = Salon::find()
                ->innerJoinWith(['usuario' => function ($query) {
                    $query->andWhere(['user.id' => self::$user_id]);
                }])->one();

            $licencia = null;

            //$licencia = new Licencia();
            $nueva_licencia = false;
            if(!$salon->licencia or $salon->licencia->estado != Licencia::ESTADO_ACTIVO) {
                $licencia = new Licencia();
                $licSpec = LicenciaSpec::findOne($tipo_licencia_id);
                $duracion = $licSpec->duracion;
                $tipo_duracion = $licSpec->tipo_duracion;

                $licencia->estado = Licencia::ESTADO_ACTIVO;
                $salon->estado_sistema = Salon::ESTADO_SISTEMA_ACTIVADO;

                $h_inicio = date_create();
                $licencia->fecha_inicio = date_format($h_inicio, 'Y-m-d H:i');
                if ($tipo_duracion == LicenciaSpec::TIPO_DURACION_DIA)
                    $tipo_duracion_str = 'days';
                elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_MES)
                    $tipo_duracion_str = 'months';
                elseif ($tipo_duracion == LicenciaSpec::TIPO_DURACION_ANNO)
                    $tipo_duracion_str = 'years';

                date_add($h_inicio, date_interval_create_from_date_string($duracion . ' ' . $tipo_duracion_str));
                $licencia->fecha_fin = date_format($h_inicio, 'Y-m-d H:i');
                $licencia->licencia_specid = $licSpec->id;
                $nueva_licencia = true;
            }else
                $licencia = $salon->licencia;


            if (!$licencia->save(false))
                throw new Exception('No se pudo guardar/actualizar la licencia');

            if ($nueva_licencia)
                $salon->link('licencia', $licencia);

            $licencia->save();
            $ipnrecord->licencia_id = $licencia->id;
            $ipnrecord->save();
            //$licencia->link('ipnNotification', $ipnrecord);

            self::updatePaymentIfExist($licencia->id, $ipnrecord->subscr_id, $ipnrecord->user_id);
            $transaction->commit();
            SalonHelper::actualizarEstadoSalon($salon->id);
            $result_code = true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getTraceAsString(), 'paypal');
            throw $e;
        }
    }

    public static function updatePaymentIfExist($idlicencia, $suscId, $userid){
        //$nots = IpnNotification::find()->where(['suscId' => $suscId, 'user_id'=> $userid, 'licencia_id' => 'is null'])->all();

        \Yii::$app->db->createCommand("UPDATE ipn_notification SET licencia_id=:lic WHERE subscr_id=:sid and user_id =:uid and licencia_id is null")
            ->bindValue(':lic', $idlicencia)
            ->bindValue(':sid', $suscId)
            ->bindValue(':uid', $userid)
            ->execute();

    }

    /*public static function saveIpnNotification($ipndata, $token){
        $lic = Licencia::find()->where(['token' => $token])->one();




    }*/

}