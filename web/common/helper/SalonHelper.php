<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 6/7/2015
 * Time: 4:55 PM
 */

namespace common\helper;

use common\models\Licencia;
use common\models\Salon;
use common\models\Servicio;
use common\models\Sillon;
use yii\base\Exception;
use yii;

class SalonHelper {

    public static function actualizarEstadoSalon($salonid = null, $usuarioid = null) {
        $findby = 0;
        if ($salonid)
            $findby = 1;
        elseif ($usuarioid)
            $findby = 2;
        else
            throw new Exception('Debe buscar por id de salon o usuario');

        //$categorias = false;
        $servicios = false;
        $sill_servs = false;
        $licencia = false;

        $salon = Salon::find()
            ->with('servicios')
            ->with('sillons')
            //->with('categorias')
            ->with('licencia');

        if ($findby == 1)
            $salon->where(['id' => $salonid]);
        elseif ($findby == 2)
            $salon->where(['usuarioid' => $usuarioid]);

        $salon = $salon->one();

        /*$cant_categorias = count($salon->categorias);
        if($cant_categorias > 0){
            $categorias = true;
        }*/

        $cant_serv = count($salon->servicios);
        if($cant_serv > 0){
            foreach($salon->servicios as $serv){
                if($serv->estado_sistema == Servicio::ESTADO_SISTEMA_ACTIVO && $serv->estado == Servicio::ESTADO_ACTIVO){
                    $servicios = true;
                    break;
                }
            }
        }

        $cant_sillones = count($salon->sillons);
        if($cant_sillones>0){
            foreach($salon->sillons as $sillon){
                $sillon_servicios = $sillon->sillonServicios;
                if(count($sillon_servicios)>0 && $sillon->estado == Sillon::ESTADO_ACTIVO){
                    $sill_servs = true;
                    break;
                }
            }
        }
		
		
        //$licencia = $salon->licencia;		
        $date = date("Y-m-d H:i:s");
		$salon_licencia = $salon->licencia;
        if($salon_licencia && $salon_licencia->estado == Licencia::ESTADO_ACTIVO){
            if($salon_licencia->fecha_fin && ($salon_licencia->fecha_fin < $date)){
                $licencia = false;
				$salon_licencia->estado = Licencia::ESTADO_INACTIVO;                
            }else{
                $licencia = true;
				$salon_licencia->estado = Licencia::ESTADO_ACTIVO;                
            }
        }
        if($salon_licencia) $salon_licencia->save();

        $update = false;
        if($servicios && $sill_servs && $licencia){
            if ($salon->estado_sistema == Salon::ESTADO_SISTEMA_INACTIVO){
                $salon->estado_sistema = Salon::ESTADO_SISTEMA_ACTIVADO;
                $update = true;
            }
        }else {
            if ($salon->estado_sistema == Salon::ESTADO_SISTEMA_ACTIVADO) {
                $salon->estado_sistema = Salon::ESTADO_SISTEMA_INACTIVO;
                $update = true;
            }
        }
        if ($update)
            $salon->save();
    }


    /*arr : Entidad Imagenes en forma de arreglo
     *
     * */
    public static function getImgUrlFromArray($arr, $thumb = false)
    {
        if(!is_array($arr))
            $arr = $arr->toArray();
        return (Yii::$app->params['salon_img_baseurl'] . '/' . $arr['salonid'] . '/' . ($thumb ? "thumb_" .$arr['nombre']: $arr['nombre'])) ;
    }

    /*arr : array de la entidad Imagenes
     * */
    public static function getImgUrlFromArrayImages($arr, $principal = true)
    {

        $img_url = '';
        if(count($arr)>0){
            foreach($arr as $img ){

                if($principal and $img['principal']){
                    if(self::existImageThumbFromArr($img))
                        $img_url = self::getImgUrlFromArray($img, true);
                    else
                        $img_url = self::getImgUrlFromArray($img);
                    break;
                }elseif(!$principal and !$img['principal']){
                    $img_url = self::getImgUrlFromArray($img);
                    break;
                }
            }
            if(!$img_url)
                $img_url =  self::getImgUrlFromArray($arr[0]);
        }

        return $img_url;
    }



    public static function getPromocionImgUrlFromArray($arr, $salonid = '')
    {
        return (Yii::$app->params['salon_img_baseurl'] . '/' . $salonid . '/promocion/' . $arr['imagen']);
    }


    public static function existImageThumbFromArr($img){

        if(!is_array($img))
            $img = $img->toArray();
        $srcimg = Yii::getAlias('@webroot'). Yii::$app->params['salones_img_path'] . '/' . $img['salonid'] . '/thumb_' . $img['nombre'];
        return file_exists($srcimg);
    }


    public static function checkServiciosEstados($salonid){
        $servicios = Servicio::find()->where(['salonid'=> $salonid])->all();
        foreach($servicios as $s){
            $sillon_servicios = $s->sillonServicios;
            $flag = false;
            foreach($sillon_servicios as $ss){
                if($ss->estado == 1){
                    $s->estado_sistema = Servicio::ESTADO_SISTEMA_ACTIVO;
                    $flag= true;
                    break;
                }
            }
            if(!$flag){
                $s->estado_sistema = Servicio::ESTADO_SISTEMA_INACTIVO;
            }
            if(!$s->save())
                Yii::info($s->getErrors());

            //echo $s->estado_sistema;
            //Yii::info($s->estado_sistema);
        }
    }
}