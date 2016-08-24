<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "imagenes".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $descripcion
 * @property string $url
 * @property integer $principal
 * @property integer $salonid
 */
class Imagenes extends \yii\db\ActiveRecord
{

    public $imgfile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'imagenes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['principal', 'salonid'], 'integer'],
            [['nombre'], 'string', 'max' => 50],
            [['descripcion'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 255],
            [['url'], 'save'],//Esto se lo agregue
            [['imgfile'], 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024 * 1024 * 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'url' => 'Url',
            'principal' => 'Principal',
            'salonid' => 'Salonid',
            'imgfile' => 'Imagen'
        ];
    }

    public function getImageurl()
    {
        return Url::to('@web/'.$this->url, true);
    }


    public function beforeSave($insert) {
        $this->imgfile = UploadedFile::getInstance($this, 'imgfile');
        if($this->imgfile)
            $this->nombre = $this->nombre . '.' . $this->imgfile->extension;
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes){
        // Save image in case of upload
        if($this->imgfile !== null)
        {
            $path = Yii::getAlias('@webroot').Yii::$app->params['salones_img_path'] . '/' .$this->salonid;
            if(!is_dir($path)){
                FileHelper::createDirectory($path);
                //mkdir($path);

            }
            $this->imgfile->saveAs($path . '/' . $this->nombre);
        }
        $this->imgfile = null;

        return parent::afterSave(true, $changedAttributes);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if( file_exists( Yii::getAlias('@webroot').Yii::$app->params['salones_img_path']  . '/' .$this->salonid . '/' . $this->nombre))
                unlink(Yii::getAlias('@webroot').Yii::$app->params['salones_img_path'] . '/' .$this->salonid . '/' . $this->nombre);
            return true;
        } else {
            return false;
        }
    }

}
