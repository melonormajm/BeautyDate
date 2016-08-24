<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Promocion;

/**
 * PromocionSearch represents the model behind the search form about `common\models\Promocion`.
 */
class PromocionSearch extends Promocion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'valor', 'activa', 'servicioid'], 'integer'],
            [['fecha_inicio', 'fecha_fin', 'operador', 'descripcion'], 'safe'],
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
        $query = Promocion::find();

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
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'valor' => $this->valor,
            'activa' => $this->activa,
            'servicioid' => $this->servicioid,
        ]);

        $query->andFilterWhere(['like', 'operador', $this->operador])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
