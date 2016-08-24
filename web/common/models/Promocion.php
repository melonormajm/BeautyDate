<?php

namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "promocion".
 *
 * @property integer $id
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $operador
 * @property integer $valor
 * @property string $descripcion
 * @property string $activa
 * @property integer $servicioid
 *
 * @property Servicio $servicio
 */
class Promocion extends \yii\db\ActiveRecord
{
    const SIGNO_IGUAL = '=';
    const SIGNO_MULTIPLICAR = '+';
    const SIGNO_DIVISION = '/';
    const SIGNO_RESTA = '-';
    const SIGNO_SUMA = '+';

    const ESTADO_ACTIVO = 'ACTIVO';
    const ESTADO_INACTIVO = 'INACTIVO';

    public $imgfile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promocion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['imagen'], 'safe'],
            [['operador', 'valor', 'servicioid','fecha_inicio', 'fecha_fin',], 'required'],
            [['valor', 'servicioid'], 'integer'],
            [['operador','activa'], 'string', 'max' => 10],
            [['descripcion'], 'string', 'max' => 255],
            [['imagen'], 'string', 'max' => 255],
            [['imgfile'], 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024 * 1024 * 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'operador' => 'Operador',
            'valor' => 'Valor',
            'descripcion' => 'Descripción',
            'activa' => 'Estado',
            'servicioid' => 'Servicio',
            'imagen'=>'Imagen'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        return $this->hasOne(Servicio::className(), ['id' => 'servicioid']);
    }

    public function getImageurl()
    {
        return Url::to('@web/'.$this->imagen, true);
    }


    public function getOpLabel(){
        $labels = [

            '-' => '% de Descuento'
        ];

        return $labels[$this->operador];
    }

    public function beforeSave($insert) {
        $this->imgfile = UploadedFile::getInstance($this, 'imgfile');
        //echo var_dump($this->imgfile);die;
        if($this->imgfile)
            $this->imagen = $this->imgfile->name;
        return parent::beforeSave($insert);
    }

    public function afterSave($insert,$changedAttributes)
    {
        $return = parent::afterSave($insert,$changedAttributes);

        if($this->imgfile !== null)
        {
            $path = Yii::getAlias('@webroot').Yii::$app->params['salones_img_path'] . '/' . $this->servicio->salonid . '/promocion' ;
            if(!is_dir($path)){
                //mkdir($path);
                FileHelper::createDirectory($path);
            }
            $this->imgfile->saveAs($path . '/' . $this->imagen);
        }
        $this->imgfile = null;

        return $return;
    }

}
