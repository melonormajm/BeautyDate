<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "detalles_pago".
 *
 * @property integer $id
 * @property integer $licenciaid
 * @property string $resultado
 * @property double $costo
 * @property string $moneda
 * @property string $cliente
 * @property string $vendedor
 * @property string $transaccion
 *
 * @property Licencia $licencia
 */
class DetallesPago extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detalles_pago';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'licenciaid'], 'integer'],
            [['costo'], 'number'],
            [['resultado', 'moneda'], 'string', 'max' => 25],
            [['cliente', 'vendedor'], 'string', 'max' => 255],
            [['transaccion'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'licenciaid' => 'Licenciaid',
            'resultado' => 'Resultado',
            'costo' => 'Costo',
            'moneda' => 'Moneda',
            'cliente' => 'Cliente',
            'vendedor' => 'Vendedor',
            'transaccion' => 'Transaccion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLicencia()
    {
        return $this->hasOne(Licencia::className(), ['id' => 'licenciaid']);
    }
}
