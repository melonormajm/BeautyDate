<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sillon".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $estado
 * @property integer $salonid
 *
 * @property Salon $salon
 * @property SillonServicio[] $sillonServicios
 */
class Sillon extends \yii\db\ActiveRecord
{
    const ESTADO_ACTIVO = 'ACTIVO';
    const ESTADO_INACTIVO = 'INACTIVO';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sillon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'salonid'], 'required'],
            [['salonid'], 'integer'],
            [['nombre'], 'string', 'max' => 20],
            [['estado'], 'string', 'max' => 50]
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
            'estado' => 'Estado',
            'salonid' => 'Salonid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalon()
    {
        return $this->hasOne(Salon::className(), ['id' => 'salonid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSillonServicios()
    {
        return $this->hasMany(SillonServicio::className(), ['sillonid' => 'id']);
    }

    public function afterSave($insert,$changedAttributes)
    {
        $return = parent::afterSave($insert, $changedAttributes);
        \common\helper\SalonHelper::actualizarEstadoSalon(null,Yii::$app->user->getId());
        return $return;
    }
}
