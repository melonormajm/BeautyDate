<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 4/15/2015
 * Time: 3:20 PM
 */

namespace common\helper;
use common\models\Servicio;
use common\models\SillonServicio;

class Reservacion {

    public static function getAvailableTimes($date, $servid, &$duracion_serv = '') {
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


}