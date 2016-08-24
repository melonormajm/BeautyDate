<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 7/9/2015
 * Time: 10:20 PM
 */
namespace common\helper;
use common\models\Promocion;


class PromocionHelper{



    /* Array promocion
     * Array servicio
     *
     * */
    public static function obtenerNuevoPrecio($p, $s){
        $nuevo_precio = '';

        if($p['operador'] == Promocion::SIGNO_RESTA){
            $nuevo_precio = $s['precio']  - $s['precio'] *  $p['valor']/100;

        }
        return $nuevo_precio;
    }

}