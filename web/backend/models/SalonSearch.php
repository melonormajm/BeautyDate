<?php

namespace backend\models;

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
           // [['id', 'usuarioid', 'licenciaid'], 'integer'],
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
        $query = Salon::find()
            ->leftJoin('licencia', 'salon.licenciaid = licencia.id')
            ->leftJoin('licencia_spec', 'licencia.licencia_specid = licencia_spec.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => ['attributes' => ['nombre', 'cantidad_sillas', 'ubicacion',
                'estado', 'hora_inicio', 'hora_fin', 'licencia.fecha_inicio',
                'licencia.fecha_fin', 'Tipo Licencia']]
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

        $query->andFilterWhere(['like', 'salon.nombre', $this->nombre])
            ->andFilterWhere(['like', 'salon.thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'salon.ubicacion', $this->ubicacion])
            ->andFilterWhere(['like', 'salon.estado', $this->estado])
            ->andFilterWhere(['like', 'salon.hora_inicio', $this->hora_inicio])
            ->andFilterWhere(['like', 'salon.hora_fin', $this->hora_fin])
            ->andFilterWhere(['like', 'salon.descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'salon.descripcion_corta', $this->descripcion_corta]);

        return $dataProvider;
    }
}
