<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 6/11/2015
 * Time: 11:51 PM
 */

namespace common\helper;
use yii;

class CategoriaHelper{


    public static function getImgUrlFromArray($arr, $img_type = 'principal', $thumbnail = true)
    {
        Yii::$app;
        if ($img_type == 'portada')
            $img = $arr['portada_img'];
        elseif ($img_type == 'servicio')
            $img = $arr['servicio_img'];
        else
            $img = $arr['thumbnail'];

        return (Yii::$app->params['categoria_img_baseurl'] . '/' . $arr['id'] . '.' . ($thumbnail ? 'thumb_' : '') . $img);
    }

}
