<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transferencia".
 *
 * @property integer $id
 * @property string $propietario
 * @property string $detalles
 * @property string $direccion
 */
class Transferencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transferencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['detalles', 'direccion'], 'string'],
            [['propietario'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'propietario' => 'Propietario',
            'detalles' => 'Detalles',
            'direccion' => 'Direccion',
        ];
    }
}
