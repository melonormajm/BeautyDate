<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "moneda".
 *
 * @property integer $id
 * @property string $simbolo
 * @property string $nombre
 * @property string $siglas
 * @property string $orden_visualizacion
 *
 * @property LicenciaSpec[] $licenciaSpecs
 * @property Salon[] $salons
 */
class Moneda extends \yii\db\ActiveRecord
{
    const ORDENV_ANTES = '0';
    const ORDENV_DESPUES = '1';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'moneda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['simbolo'], 'string', 'max' => 10],
            [['nombre'], 'string', 'max' => 255],
            [['siglas'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'simbolo' => 'Simbolo',
            'nombre' => 'Nombre',
            'siglas' => 'Siglas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLicenciaSpecs()
    {
        return $this->hasMany(LicenciaSpec::className(), ['moneda_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalons()
    {
        return $this->hasMany(Salon::className(), ['monedaid' => 'id']);
    }

    public function getDisplay($valor) {
        return ($this->orden_visualizacion == Moneda::ORDENV_ANTES ? $this->simbolo : '') .
            ' ' . $valor . ' ' . ($this->orden_visualizacion == Moneda::ORDENV_DESPUES ? $this->simbolo : '');
    }
}
