<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sillon_servicio".
 *
 * @property integer $id
 * @property integer $sillonid
 * @property integer $servicioid
 *
 * @property Reservacion[] $reservacions
 * @property Servicio $servicio
 * @property Sillon $sillon
 */
class SillonServicio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sillon_servicio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sillonid', 'servicioid'], 'required'],
            [['sillonid', 'servicioid', 'estado'], 'integer'],
            [['sillonid', 'servicioid'], 'unique', 'targetAttribute' => ['sillonid', 'servicioid'], 'message' => 'The combination of Sillonid and Servicioid has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sillonid' => 'Sillonid',
            'servicioid' => 'Servicioid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReservacions()
    {
        return $this->hasMany(Reservacion::className(), ['sillon_servicioid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicio()
    {
        return $this->hasOne(Servicio::className(), ['id' => 'servicioid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSillon()
    {
        return $this->hasOne(Sillon::className(), ['id' => 'sillonid']);
    }
}
