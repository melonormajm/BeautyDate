<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\IpnNotification;

/**
 * SalonSearch represents the model behind the search form about `app\models\Salon`.
 */
class IpnNotificationSearch extends IpnNotification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['txn_type', 'subscr_id', 'receiver_email', 'payer_email', 'msg_date', 'item_name', 'txn_id', 'payment_type','payment_status', 'mc_gross', 'mc_currency' ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = IpnNotification::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            /*'sort' =>[ 'attributes' => [
                            'fecha' =>[
                                'desc' =>['fecha'=> SORT_DESC],
                                'asc' =>['fecha' => SORT_ASC ],
                                'default' => SORT_DESC,
                                'label' => 'Fecha',
                            ]
                        ]
                    ]*/
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'licencia_id' => $this->licencia_id,
        ]);



       /* $query->andFilterWhere([
            'id' => $this->id,
            'cantidad_sillas' => $this->cantidad_sillas,
            'usuarioid' => $this->usuarioid,
            'licenciaid' => $this->licenciaid,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'hora_inicio', $this->hora_inicio])
            ->andFilterWhere(['like', 'hora_fin', $this->hora_fin])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'descripcion_corta', $this->descripcion_corta]);
        */
        return $dataProvider;
    }
}
