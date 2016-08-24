<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 3/22/2015
 * Time: 2:43 PM
 */

namespace common\models;


use yii\base\Exception;
use common\models\Licencia;

class Enum {

    const TIPO_DURACION_DIA = 'DIA';
    const TIPO_DURACION_MES = 'MES';
    const TIPO_DURACION_ANNO = 'ANNO';

    const TIPO_LICENCIA_ONEPAY = 'UNPAGO';
    const TIPO_LICENCIA_SUSCRIPTION = 'SUSCRIPCION';
    const TIPO_LICENCIA_TRAIL = 'PRUEBA';

    const  ESTADO_ACTIVO = 'ACTIVO';
    const  ESTADO_NOACTIVO = 'NOACTIVO';

    private $labels = [
        'tipo_duracion' => ['dia' => 'Día', 'mes' => 'Mes', 'anno' => 'Año']
    ];

    public function getTipoDuracionLabel($enum) {
        return $this->labels['tipo_duracion'][$enum];
    }

    public static function getPlural($enum) {
        if ($enum == static::TIPO_DURACION_MES)
            return 'Meses';
        elseif ($enum == static::TIPO_DURACION_ANNO)
            return 'Años';
        elseif ($enum == static::TIPO_DURACION_DIA)
            return 'Dias';
    }

    public static function listTipoDuracion() {
        return [
            static::TIPO_DURACION_DIA => 'Dia',
            static::TIPO_DURACION_MES => 'Mes',
            static::TIPO_DURACION_ANNO => 'Año',
        ];
    }

    public static function listTipoLicencia() {
        return [
            static::TIPO_LICENCIA_ONEPAY => 'Un pago',
            static::TIPO_LICENCIA_SUSCRIPTION => 'Suscripcion',
            static::TIPO_LICENCIA_TRAIL  => 'Período de prueba'
        ];
    }

    public static function listEstadoLicencia() {
        return [
            Licencia::ESTADO_INACTIVO => 'Inactivo',
            Licencia::ESTADO_ACTIVO=> 'Activo',
            Licencia::ESTADO_PROCESANDO=>'Procesando',
            Licencia::ESTADO_VENCIDO=>'Vencido',
            Licencia::ESTADO_OLVIDADA=>'Olvidada',
        ];
    }

    public static function listEstado() {
        return [
            static::ESTADO_ACTIVO => 'Activo',
            static::ESTADO_NOACTIVO => 'Inactivo',
        ];
    }


    public static function getLabel($enum){
        $labels = [
            static::ESTADO_ACTIVO => 'Activo',
            static::ESTADO_NOACTIVO => 'Inactivo',

            static::TIPO_LICENCIA_ONEPAY        => 'Un pago',
            static::TIPO_LICENCIA_SUSCRIPTION   => 'Suscripcion',
            static::TIPO_LICENCIA_TRAIL         => 'Período de prueba',

            static::TIPO_DURACION_DIA => 'Dia',
            static::TIPO_DURACION_MES => 'Mes',
            static::TIPO_DURACION_ANNO => 'Año',

        ];

        if($enum){
            try{
                return $labels[$enum]  ;
            }catch (Exception $e){
                return '';
            }
        }
    }
}