<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 4/15/2015
 * Time: 3:20 PM
 */

namespace common\helper;
use common\models\Servicio;
use common\models\Sillon;
use common\models\SillonServicio;
use yii\base\Exception;
use yii\db\Query;

class ReservacionHelper {

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

    public static function getAvailablehours($servid, $date) {
        //return array_keys(self::getAvailabledates1($servid, $date));
        return self::getAvailabledates1($servid, $date);
    }

    public static function getAvailabledates1($servid, $fecha_inicio, $fecha_fin = null) {
        if ($fecha_fin == null)
            $fecha_fin = $fecha_inicio;

        //obtengo el servicio solicitado con su salon
        $servicio = Servicio::find()->with('salon')
            ->where([ 'id' => $servid, 'estado' => Servicio::ESTADO_ACTIVO])
            ->one();

        if (!$servicio) throw new Exception('No existe el servicio especificado, o está fuera de servicio');

        $duracion = $servicio->duracion;
        $horario_inicio = $servicio->horario_inicio;
        $horario_fin = $servicio->horario_fin;
        $hora_inicio_salon = $servicio->salon->hora_inicio;
        $hora_fin_salon = $servicio->salon->hora_fin;
        //ver como se obtiene con valor agregado o consulta agregada
        $cant_sillones = $servicio->getSillonServicios()->count();

        if(!$cant_sillones) throw new Exception('No existen sillones asociado al servicio especificado');

        // sillones que brindan el servicio especificado
        $sillones = (new Query())->select('ss.sillonid')->from('sillon_servicio ss')
            ->innerJoin('sillon s', 's.id = ss.sillonid')
            ->where(['ss.estado' => 1])
            ->andWhere(['s.estado' => Sillon::ESTADO_ACTIVO])
            ->andWhere(['ss.servicioid' => $servicio->id])
            ->indexBy('sillonid')
            ->all();
        $sillones_ids = array_keys($sillones);

        $reservaciones = (new Query())->select('r.id, fecha, hora_inicio, hora_fin, ss.sillonid')
            ->from('reservacion r')
            ->innerJoin('sillon_servicio ss', 'r.sillon_servicioid = ss.id')
            ->where(['ss.sillonid' => $sillones_ids, 'r.estado' => \common\models\Reservacion::ESTADO_PENDIENTE])
            ->andWhere(['between', 'r.fecha', $fecha_inicio, $fecha_fin])
            ->orderBy('fecha, hora_inicio')->all();

        // sillones que tienen reservaciones en la fecha especificada
        $sillones_reserv = [];
        foreach ($reservaciones as $reserv) {
            if (!in_array($reserv['sillonid'], $sillones_reserv))
                $sillones_reserv[] = $reserv['sillonid'];
        }

        //echo "sillones_ids";
        //var_dump($sillones_ids);

        //echo "sillones_reserv";
        //print_r($sillones_reserv);

        $horarios_disponibles = [];

        //existe algun sillon sin reservacion, por lo tanto todos los horarios en el rango estan disponibles
        $sillones_sin_reservacion = array_diff($sillones_ids, $sillones_reserv);

        //echo "sillones_sin_reservacion";
        //var_dump($sillones_sin_reservacion);

        if (!empty($sillones_sin_reservacion)) {
            self::setIntervalToSillon($horario_inicio, $horario_fin, $horarios_disponibles, $duracion, array_pop($sillones_sin_reservacion));
            //echo '<pre>'; echo 'Horarios Disponibles<br>'; print_r($horarios_disponibles);die;
            return $horarios_disponibles;
        }

        $availableTimes = [];
        //echo '<pre>Reservaciones<br>';print_r($reservaciones);

        foreach ($reservaciones as $reserv){
            if (!isset($availableTimes[$reserv['fecha']]))
                $availableTimes[$reserv['fecha']] = [];
            $fecha_data = &$availableTimes[$reserv['fecha']];
            if (!isset($fecha_data[$reserv['sillonid']]))
                $fecha_data[$reserv['sillonid']] = [['i'=>$hora_inicio_salon, 'f'=>$hora_fin_salon]];
            $sillon_data = &$fecha_data[$reserv['sillonid']];
            foreach ($sillon_data as $k => $interval) {
                if ($reserv['hora_inicio'] >= $interval['i'] && $reserv['hora_fin'] <= $interval['f']) {
                    unset($sillon_data[$k]);
                    if ($reserv['hora_inicio'] != $interval['i'])
                        $sillon_data[] = ['i' => $interval['i'], 'f' => $reserv['hora_inicio']];
                    if ($reserv['hora_fin'] != $interval['f'])
                        $sillon_data[] = ['i' => $reserv['hora_fin'], 'f' => $interval['f']];
                    break;
                }
            }
        }
        //echo '<pre>'. 'Available Times: <br>'; print_r($availableTimes);

        $availableDays = [];
        foreach ($availableTimes as $k_date => $date_item) {
            foreach ($date_item as $sillid => $sillon_item) {
                foreach ($sillon_item as $interval_item) {
                    self::setIntervalToSillon($interval_item['i'], $interval_item['f'], $horarios_disponibles, $duracion, $sillid);

//                    //el intervalo de disponibilidad es mayor que mi horario de servicio
//                    if ($interval_item['i'] < $horario_inicio && $interval_item['f'] > $horario_fin) {
//                        self::setIntervalToSillon($horario_inicio, $horario_fin, $horarios_disponibles, $duracion, $sillid);
//                    }
//                    //el intervalo de disponibilidad esta fuera de mi horario de servicio
//                    elseif ( ($interval_item['i'] < $horario_inicio && $interval_item['f'] <= $horario_inicio) ||
//                        $interval_item['i'] >= $horario_fin && $interval_item['f'] > $horario_fin ) {
//                    }
//                    elseif ($interval_item['i'] <= $horario_inicio) {
//                        if ($valid_date = self::esIntervaloValido($horario_inicio, $interval_item['f'], $duracion))
//                            self::setIntervalToSillon($horario_inicio, $interval_item['f'], $horarios_disponibles, $duracion, $sillid);
//                    } else {
//                        if ($valid_date = self::esIntervaloValido($interval_item['i'], $horario_fin, $duracion))
//                            self::setIntervalToSillon($interval_item['i'], $horario_fin, $horarios_disponibles, $duracion, $sillid);
//                    }
                }
            }
        }
        //echo '<pre>'; echo 'Horarios Disponibles<br>'; print_r($horarios_disponibles);die;
        return $horarios_disponibles;
    }

    public static function getBusydays($fecha_inicio, $fecha_fin, $servid) {
        $servicio = Servicio::find()->with('salon')
            ->where([ 'id' => $servid, 'estado' => Servicio::ESTADO_ACTIVO])
            ->one();

        if (!$servicio) throw new Exception('No existe el servicio especificado, o está fuera de servicio');

        $duracion = $servicio->duracion;
        $horario_inicio = $servicio->horario_inicio;
        $horario_fin = $servicio->horario_fin;
        $hora_inicio_salon = $servicio->salon->hora_inicio;
        $hora_fin_salon = $servicio->salon->hora_fin;
        //ver como se obtiene con valor agregado o consulta agregada
        $cant_sillones = $servicio->getSillonServicios()->count();

        if(!$cant_sillones) throw new Exception('No existen sillones asociado al servicio especificado');

        $sillones = (new Query())->select('ss.sillonid')->from('sillon_servicio ss')
            ->innerJoin('sillon s', 's.id = ss.sillonid')
            ->where(['ss.estado' => 1])
            ->andWhere(['s.estado' => Sillon::ESTADO_ACTIVO])
            ->andWhere(['ss.servicioid' => $servicio->id])
            ->indexBy('sillonid')
            ->all();

        $sillones_ids = array_keys($sillones);
        $reservaciones = (new Query())->select('r.id, fecha, hora_inicio, hora_fin, ss.sillonid')
            ->from('reservacion r')
            ->innerJoin('sillon_servicio ss', 'r.sillon_servicioid = ss.id')
            ->where(['ss.sillonid' => $sillones_ids/*, 'r.estado' => Reservacion::ESTADO_PENDIENTE*/])
            ->andWhere(['between', 'r.fecha', $fecha_inicio, $fecha_fin])
            ->orderBy('fecha, hora_inicio')->all();

        $sillones_reserv = [];
        foreach ($reservaciones as $reserv) {
            if (!in_array($reserv['sillonid'], $sillones_reserv))
                $sillones_reserv[] = $reserv['sillonid'];
        }

        //existe algun sillon sin reservacion, por lo tanto todos los dias en el rango estan disponibles
        $sillones_sin_reservacion = array_diff($sillones_ids, $sillones_reserv);

        //Esto lo pase para aqui porque aqui se necesita tener todos los dias del mes
        $allDays = self::getAllDaysInInterval1($fecha_inicio, $fecha_fin);

        if (!empty($sillones_sin_reservacion))
            return self::getSalonDisableDays($servicio->salon->dias_no_laborables, $allDays); //return [];

        $availableTimes = [];
        //echo '<pre>Reservaciones<br>';print_r($reservaciones);

        foreach ($reservaciones as $reserv){
            if (!isset($availableTimes[$reserv['fecha']]))
                $availableTimes[$reserv['fecha']] = [];
            $fecha_data = &$availableTimes[$reserv['fecha']];
            if (!isset($fecha_data[$reserv['sillonid']]))
                $fecha_data[$reserv['sillonid']] = [['i'=>$hora_inicio_salon, 'f'=>$hora_fin_salon]];
            $sillon_data = &$fecha_data[$reserv['sillonid']];
            foreach ($sillon_data as $k => $interval) {
                if ($reserv['hora_inicio'] >= $interval['i'] && $reserv['hora_fin'] <= $interval['f']) {
                    unset($sillon_data[$k]);
                    if ($reserv['hora_inicio'] != $interval['i'])
                        $sillon_data[] = ['i' => $interval['i'], 'f' => $reserv['hora_inicio']];
                    if ($reserv['hora_fin'] != $interval['f'])
                        $sillon_data[] = ['i' => $reserv['hora_fin'], 'f' => $interval['f']];
                    break;
                }
            }
        }
        //echo '<pre>' . 'Available Times: <br>'; print_r($availableTimes);die;

        $availableDays = [];
        foreach ($availableTimes as $k_date => $date_item) {
            $valid_date = false;

            if (count($date_item) < $cant_sillones)
                $valid_date = true;

            if (!$valid_date) {
                foreach ($date_item as $sillon_item) {
                    foreach ($sillon_item as $interval_item) {
                        $valid_date = true;
                        break;
//                    //el intervalo de disponibilidad es mayor que mi horario de servicio
//                    if ($interval_item['i'] < $horario_inicio && $interval_item['f'] > $horario_fin) {
//                        $valid_date = true;
//                        break;
//                    }
//                    //el intervalo de disponibilidad esta fuera de mi horario de servicio
//                    if ( ($interval_item['i'] < $horario_inicio && $interval_item['f'] <= $horario_inicio) ||
//                        $interval_item['i'] >= $horario_fin && $interval_item['f'] > $horario_fin ) {
//                        continue;
//                    }
//                    if ($interval_item['i'] <= $horario_inicio) {
//                        if ($valid_date = $this->esIntervaloValido($horario_inicio, $interval_item['f'], $duracion))
//                            break;
//                    } else {
//                        if ($valid_date = $this->esIntervaloValido($interval_item['i'], $horario_fin, $duracion))
//                            break;
//                    }
                    }
                    if ($valid_date)
                        break;
                }
            }

            if ($valid_date)
                $availableDays[] = $k_date;
        }
        //echo '<pre>' . 'Available Days<br>'; print_r($availableDays); die;

//        $date_inicio = date_create($fecha_inicio);
//        $date_fin = date_create($fecha_fin);
//        $allDays = self::getAllDaysInInterval(date_format($date_inicio, 'Y'), date_format($date_inicio, 'm'),
//            date_format($date_inicio, 'j'), date_format($date_fin, 'j'));

        //$allDays = self::getAllDaysInInterval1($fecha_inicio, $fecha_fin);

        //dejando los dias que no tienen ninguna reservacion
        $av = array_diff($allDays, array_keys($availableTimes));
        //adicionando los dias disponibles del analisis
        $av = array_merge($av, $availableDays);
        $av = array_unique($av);

        //echo '<pre>' . 'All Available Days<br>'; print_r($av); die;
        //echo '<pre>' . 'AllDays: <br>'; print_r($allDays); die;

        //obteniendo los dias no disponibles del intervalo basado en los disponibles
        $av = array_diff($allDays, $av);

        //obteniendo los dias deshabilitados
        $disablesDays = self::getSalonDisableDays($servicio->salon->dias_no_laborables, $allDays);
        return array_merge($disablesDays, $av);
        //echo '<pre>' . 'Not Available Days: <br>'; print_r($av); die;

        //return $av;
    }

    private static function setIntervalToSillon($hora_inicio, $hora_fin, &$horarios_disponibles, $duracion, $sillonid = null) {
        $h_inicio = date_create($hora_inicio);
        $h_fin = date_create($hora_fin);

        while ($h_inicio < $h_fin) {
            if ($sillonid)
                $horarios_disponibles[date_format($h_inicio, 'Hi')][] = $sillonid;
            else
                $horarios_disponibles[date_format($h_inicio, 'Hi')] = [];
            date_add($h_inicio, date_interval_create_from_date_string($duracion . ' minutes'));
        }
    }

    private static function esIntervaloValido($inicio, $fin, $duracion) {
        $di = date_create($inicio);
        $df = date_create($fin);
        date_add($di, date_interval_create_from_date_string($duracion . ' minutes'));
        return $di <= $df;
    }

    private static function getAllDaysInInterval($anno, $mes, $dia_i, $dia_f) {
        $result = [];
        for ($i = $dia_i; $i <= $dia_f; $i++)
            $result[] = $anno . '-' . $mes . '-' . ($i < 10 ? '0'.$i : $i);
        return $result;
    }

    public static function getAllDaysInInterval1($fecha_i, $fecha_f) {
        $result = [];
        $fecha_i_obj = date_create($fecha_i);
        while ($fecha_i <= $fecha_f) {
            $result[] = $fecha_i;
            date_add($fecha_i_obj, date_interval_create_from_date_string('1 day'));
            $fecha_i = date_format($fecha_i_obj, 'Y-m-d');
        }
        return $result;
    }


    public static function getSalonDisableDays($dd, $monthDays){
        $ddays = [];
        if(!isset($dd)) return $ddays;
        $dda = explode(',', $dd);
        foreach($monthDays as $d){
            $dateW = date('w', strtotime($d));
            if(in_array($dateW, $dda))
                $ddays[] = $d;
        }
        return $ddays;


    }
}