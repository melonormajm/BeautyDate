<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "licencia".
 *
 * @property integer $id
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property integer $licencia_specid
 * @property string $estado
 * @property string $detalles
 *
 * @property LicenciaSpec $licenciaSpec
 * @property Salon[] $salons
 */
class Licencia extends \yii\db\ActiveRecord
{
    const ESTADO_ACTIVO = 'ACTIVO';
    const ESTADO_INACTIVO = 'INACTIVO';
    const ESTADO_VENCIDO = 'VENCIDO';
    const ESTADO_PROCESANDO = 'PROCESANDO';
    const ESTADO_OLVIDADA = 'OLVIDADA';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'licencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['licencia_specid'], 'required'],
            [['licencia_specid'], 'integer']
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
            'licencia_specid' => 'Tipo de Licencia',
            'detalles'=> 'Detalles'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLicenciaSpec()
    {
        return $this->hasOne(LicenciaSpec::className(), ['id' => 'licencia_specid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropietario()
    {
        return $this->hasOne(User::className(), ['id' => 'usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalons()
    {
        return $this->hasMany(Salon::className(), ['licenciaid' => 'id']);
    }

    public function getDetallesPago()
    {
        return $this->hasMany(DetallesPago::className(), ['licenciaid' => 'id']);
    }

    public function getIpnNotification()
    {
        return $this->hasMany(IpnNotification::className(), ['licencia_id' => 'id']);
    }

    public function extraFields()
    {
        return ['licenciaSpec'];
    }

    public function afterSave($insert,$changedAttributes)
    {
        $return = parent::afterSave($insert, $changedAttributes);
        //TODO ESTO HAY QUE QUITARLO
        //PQ ESTO NO FLUYE CUANDO SE EJECUTA EN EL BACKEND. EL USUARIO LOGUEADO NO ES EL PROPIETARIO DEL SALON
//        \common\helper\SalonHelper::actualizarEstadoSalon(null,Yii::$app->user->getId());
        return $return;
    }



}
