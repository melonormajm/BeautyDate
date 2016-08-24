<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ipn_notification".
 *
 * @property integer $id
 * @property string $txn_type
 * @property string $subscr_id
 * @property string $receiver_email
 * @property string $msg_date
 * @property string $item_name
 * @property string $txn_id
 * @property string $payment_type
 * @property string $payment_status
 * @property string $mc_gross
 * @property string $mc_currency
 * @property integer $user_id
 * @property integer $licencia_id
 *
 * @property Licencia $licencia
 * @property User $user
 */
class IpnNotification extends \yii\db\ActiveRecord
{
    //Subscriber
    const TNX_TYPE_SIGNUP = 'subscr_signup';
    const TNX_TYPE_PAYMENT = 'subscr_payment';
    const TNX_TYPE_EOT  = 'subscr_eot';
    const TNX_TYPE_CANCELLED  = 'subscr_cancel';

    //Payment now
    const TNX_TYPE_WEB_ACEPT = 'web_accept';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ipn_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msg_date'], 'safe'],
            [['user_id', 'licencia_id'], 'integer'],
            [['txn_type', 'subscr_id', 'txn_id', 'mc_gross'], 'string', 'max' => 50],
            [['receiver_email', 'payer_email'], 'string', 'max' => 255],
            [['item_name'], 'string', 'max' => 200],
            [['payment_type', 'payment_status'], 'string', 'max' => 25],
            [['mc_currency'], 'string', 'max' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'txn_type' => 'Txn Tipo',
            'subscr_id' => 'Subscripcion ID',
            'receiver_email' => 'Email Receptor',
            'payer_email'   => 'Email Cliente',
            'msg_date' => 'Fecha',
            'item_name' => 'Item Name',
            'txn_id' => 'Transaccion id',
            'payment_type' => 'Tipo del pago',
            'payment_status' => 'Estado del pago',
            'mc_gross' => 'Cantidad',
            'mc_currency' => 'Moneda',
            'user_id' => 'Usuario ID',
            'licencia_id' => 'Licencia ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLicencia()
    {
        return $this->hasOne(Licencia::className(), ['id' => 'licencia_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    public static function getLabelTypeTxn($txn){
        $labels = [
            self::TNX_TYPE_SIGNUP => 'Suscripción',
            self::TNX_TYPE_PAYMENT=> 'Pago',
            self::TNX_TYPE_CANCELLED=>'Cancelado',
            self::TNX_TYPE_EOT=>'Expirado',
            self::TNX_TYPE_WEB_ACEPT => 'Pago Web aceptado'
        ];

        return $labels[$txn];
    }
}
