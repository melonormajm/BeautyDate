<?php

namespace common\models;

use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * This is the model class for table "categoria".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $descripcion
 * @property integer $orden
 *
 * @property CategoriaSalon[] $categoriaSalons
 * @property Salon[] $salons
 */
class Categoria extends \yii\db\ActiveRecord
{

    public $thumbnailimg;
    public $portada_imgimg;
    public $servicio_imgimg;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoria';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'orden'], 'required'],
            [['nombre'], 'string', 'max' => 150],
            [['orden'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
            [['portada_imgimg'], 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024 * 1024 * 4],
            [['servicio_imgimg'], 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024 * 1024 * 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'nombre' => Yii::t('common', 'Name'),
            'orden' => Yii::t('common', 'Order'),
            'descripcion' => Yii::t('common', 'Description'),
            'thumbnailimg' => Yii::t('common', 'Image'),
            'portada_imgimg' => Yii::t('common', 'Portrait Image'),
            'servicio_imgimg' => Yii::t('common', 'Service Image'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriaSalons()
    {
        return $this->hasMany(CategoriaSalon::className(), ['categoriaid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalons()
    {
        return $this->hasMany(Salon::className(), ['id' => 'salonid'])->viaTable('categoria_salon', ['categoriaid' => 'id']);
    }

    public function getImageurl($img_type = 'principal', $thumbnail = true)
    {
        if ($img_type == 'portada')
            $img = $this->portada_img;
        elseif ($img_type == 'servicio')
            $img = $this->servicio_img;
        else
            $img = $this->thumbnail;

        return (Yii::$app->params['categoria_img_baseurl'] . '/' . $this->id . '.' . ($thumbnail ? 'thumb_' : '') . $img);
        return Url::to(Yii::$app->params['categoria_img_baseurl'] . '/' . $this->id . '.'
            . ($thumbnail ? 'thumb_' : '') . $img, true);
    }

    public function getImagePath($img_type = 'principal', $thumbnail = true)
    {
        if ($img_type == 'portada')
            $img = $this->portada_img;
        elseif ($img_type == 'servicio')
            $img = $this->servicio_img;
        else
            $img = $this->thumbnail;

        return Yii::getAlias(Yii::$app->params['categoria_img_path'] . '/' . $this->id . '.'
            . ($thumbnail ? 'thumb_' : '') . $img, true);
    }

    public function beforeSave($insert) {

        $this->thumbnailimg = UploadedFile::getInstance($this, 'thumbnailimg');
        if($this->thumbnailimg !== null){
            $this->thumbnail = 'c.'. strtolower($this->thumbnailimg->extension);
        }
        $this->portada_imgimg = UploadedFile::getInstance($this, 'portada_imgimg');
        if($this->portada_imgimg !== null){
            $this->portada_img = 'c_portada.'. strtolower($this->portada_imgimg->extension);
        }
        $this->servicio_imgimg = UploadedFile::getInstance($this, 'servicio_imgimg');
        if($this->servicio_imgimg !== null){
            $this->servicio_img = 'c_servicio.'. strtolower($this->servicio_imgimg->extension);
        }
        return parent::beforeSave($insert);

    }

    public function afterSave($insert, $changedAttributes){

        // Save image in case of upload
        if($this->thumbnailimg !== null)
        {
            $camino = $this->getImagePath('principal', false);
            $camino_thumb = $this->getImagePath();
            $this->thumbnailimg->saveAs($camino);
            Image::thumbnail($camino, 120, 120, ManipulatorInterface::THUMBNAIL_INSET)
                ->save($camino_thumb, ['quality' => 50]);
        }
        $this->thumbnailimg = null;

        if($this->portada_imgimg !== null) {
            $camino = $this->getImagePath('portada', false);
            $camino_thumb = $this->getImagePath('portada');
            $this->portada_imgimg->saveAs($camino);
            Image::thumbnail($camino, 120, 120, ManipulatorInterface::THUMBNAIL_INSET)
                ->save($camino_thumb, ['quality' => 50]);
        }
        $this->portada_imgimg = null;

        if($this->servicio_imgimg !== null) {
            $camino = $this->getImagePath('servicio', false);
            $camino_thumb = $this->getImagePath('servicio');
            $this->servicio_imgimg->saveAs($camino);
            Image::thumbnail($camino, 120, 120, ManipulatorInterface::THUMBNAIL_INSET)
                ->save($camino_thumb, ['quality' => 50]);
        }
        $this->servicio_imgimg = null;

        return parent::afterSave($insert, $changedAttributes);

    }

    public function afterDelete()
    {
        if ($this->thumbnail && file_exists($this->getImagePath('principal', false)))
    		unlink($this->getImagePath('principal', false));
        if ($this->thumbnail && file_exists($this->getImagePath('principal')))
            unlink($this->getImagePath('principal'));
        if ($this->portada_img && file_exists($this->getImagePath('portada', false)))
            unlink($this->getImagePath('portada', false));
        if ($this->portada_img && file_exists($this->getImagePath('portada')))
            unlink($this->getImagePath('portada'));
        if ($this->servicio_img && file_exists($this->getImagePath('servicio', false)))
            unlink($this->getImagePath('servicio', false));
        if ($this->servicio_img && file_exists($this->getImagePath('servicio')))
            unlink($this->getImagePath('servicio'));

        parent::afterDelete();
    }

}
