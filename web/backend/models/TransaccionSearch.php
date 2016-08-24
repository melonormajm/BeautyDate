<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DetallesPago;

/**
 * SalonSearch represents the model behind the search form about `app\models\Salon`.
 */
class TransaccionSearch extends DetallesPago
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['costo', 'resultado', 'moneda', 'cliente', 'transaccion'], 'safe'],
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
        $query = DetallesPago::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' =>[ 'attributes' => [
                            'fecha' =>[
                                'desc' =>['fecha'=> SORT_DESC],
                                'asc' =>['fecha' => SORT_ASC ],
                                'default' => SORT_DESC,
                                'label' => 'Fecha',
                            ]
                        ]
                    ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'licenciaid' => $this->licenciaid,
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
