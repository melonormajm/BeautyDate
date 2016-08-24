<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Salon;

/**
 * SalonSearch represents the model behind the search form about `app\models\Salon`.
 */
class SalonSearch extends Salon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cantidad_sillas', 'usuarioid', 'licenciaid'], 'integer'],
            [['nombre', 'thumbnail', 'ubicacion', 'estado', 'hora_inicio', 'hora_fin', 'descripcion', 'descripcion_corta'], 'safe'],
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
        $query = Salon::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
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

        return $dataProvider;
    }
}
