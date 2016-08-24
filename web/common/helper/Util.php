<?php
/**
 * Created by PhpStorm.
 * User: juanma
 * Date: 7/5/2015
 * Time: 9:33 PM
 */

namespace common\helper;

class Util{

    public static function modelError2String($errs, $str_union = ' '){

        $err = array();

        foreach ($errs as $e) {
            $err  = array_merge($err, $e);
        }
        return implode($str_union, $err);
    }


    public static function gd_extension($full_path_to_image='')
    {
        $extension = 'null';
        if($image_type = exif_imagetype($full_path_to_image))
        {
            $extension = image_type_to_extension($image_type, false);
        }
        $known_replacements = array(
            'jpeg' => 'jpg',
            'tiff' => 'tif',
        );
        $extension = str_replace(array_keys($known_replacements), array_values($known_replacements), $extension);

        return $extension;
    }

}