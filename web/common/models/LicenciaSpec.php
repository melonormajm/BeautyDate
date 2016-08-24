<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "licencia_spec".
 *
 * @property integer $id
 * @property double $precio
 * @property integer $duracion
 * @property string $tipo_duracion
 *
 * @property Licencia[] $licencias
 */
class LicenciaSpec extends \yii\db\ActiveRecord
{
    /**
     * TODO
     * Hay que revisa si los enums estos no se estan usando en mas ningun lado en la aplicacion para quitarlos,
     * deberian usarse los de la clase enum
     */
    const TIPO_DURACION_DIA = 'DIA';
    const TIPO_DURACION_MES = 'MES';
    const TIPO_DURACION_ANNO = 'ANNO';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'licencia_spec';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'precio', 'duracion', 'tipo_duracion', 'moneda_id', 'estado', 'tipo'], 'required'],
            [['precio'], 'number'],
            [['duracion'], 'integer'],
            [['tipo_duracion'], 'string', 'max' => 20],
            [['descripcion', 'hosted_button_id'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        // Esto es solo para para generar la traduccion de los enumeradores
        Yii::t('common', 'DIA');
        Yii::t('common', 'MES');
        Yii::t('common', 'ANNO');

        return [
            'id' => Yii::t('common', 'ID'),
            'precio' => Yii::t('common', 'Price'),
            'duracion' => Yii::t('common', 'Duration'),
            'tipo_duracion' => Yii::t('common', 'Duration Type'),
            'nombre' => Yii::t('common', 'Name'),
            'descripcion' => Yii::t('common', 'Description'),
            'moneda_id' => Yii::t('common', 'Currency'),
            'estado' =>Yii::t('common', 'Estado'),
            'tipo' => Yii::t('common', 'Tipo de licencia'),
            'hosted_button_id' => Yii::t('common', 'ID boton paypal'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLicencias()
    {
        return $this->hasMany(Licencia::className(), ['licencia_specid' => 'id']);
    }

    public function getMoneda()
    {
        return $this->hasOne(Moneda::className(), ['id' => 'moneda_id']);
    }
/*
    public function getDisplayname() {
        return $this->nombre . ' (' .  . ')';
    }*/
}
