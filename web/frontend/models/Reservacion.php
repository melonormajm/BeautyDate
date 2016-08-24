<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reservacion".
 *
 * @property integer $id
 * @property string $estado
 * @property string $fecha
 * @property string $aplicacion_cliente
 * @property integer $usuarioid
 * @property integer $sillon_servicioid
 * @property integer $evaluacion
 *
 * @property Calificacion[] $calificacions
 * @property Usuario $usuario
 * @property SillonServicio $sillonServicio
 */
class Reservacion extends \yii\db\ActiveRecord
{
    const ESTADO_CANCELADA = 'CANCELADA';
    const ESTADO_PENDIENTE = 'PENDIENTE';
    const ESTADO_EJECUTADA = 'EJECUTADA';
    const ESTADO_PRERESERVADO = 'PRERESERVADO';

    const APPCLIENT_APP = 'APP';
    const APPCLIENT_WEB = 'WEB';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reservacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['estado', 'aplicacion_cliente', 'usuarioid', 'sillon_servicioid'], 'required'],
            [['fecha'], 'safe'],
            [['usuarioid', 'sillon_servicioid'], 'integer'],
            [['estado'], 'string', 'max' => 255],
            [['aplicacion_cliente'], 'string', 'max' => 25],
            ['evaluacion', 'in', 'range' => [1,2,3,4,5]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'estado' => 'Estado',
            'fecha' => 'Fecha',
            'aplicacion_cliente' => 'Aplicacion Cliente',
            'usuarioid' => 'Usuarioid',
            'sillon_servicioid' => 'Sillon Servicioid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalificacions()
    {
        return $this->hasMany(Calificacion::className(), ['reservacionid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(User::className(), ['id' => 'usuarioid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSillonServicio()
    {
        return $this->hasOne(SillonServicio::className(), ['id' => 'sillon_servicioid']);
    }
}
