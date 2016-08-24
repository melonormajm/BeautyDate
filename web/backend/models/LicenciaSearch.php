<?php

namespace backend\models;

use common\models\Salon;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Licencia;

/**
 * SalonSearch represents the model behind the search form about `app\models\Salon`.
 */
class LicenciaSearch extends Licencia
{
    public $espec_nombre;
    public $propietario_Viejo;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
           // [['id', 'usuarioid', 'licenciaid'], 'integer'],
            [['fecha_inicio', 'fecha_fin', 'detalles', 'estado', 'espec_nombre', 'propietario_Viejo'], 'safe'],
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

        $licencias_con_salones = Salon::find()
            ->select('id')
            ->where('licenciaid is not null')
            ->indexBy('id')
            ->asArray()->all();
        $licencias_con_salones = array_keys($licencias_con_salones);

        $query = Licencia::find()
            ->where(['not in', 'licencia.id', $licencias_con_salones])
            ->joinWith('licenciaSpec')
            ->joinWith('propietario');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => ['attributes' => ['estado', 'duracion', 'Salon', 'espec_nombre' =>  [
                            'asc' => ['licencia_spec.nombre' => SORT_ASC],
                            'desc' => ['licencia_spec.nombre' => SORT_DESC],
                        ],
                        'propietario_Viejo'   => [
                            'asc' => ['user.email' => SORT_ASC],
                            'desc' => ['user.email' => SORT_DESC],
                        ]
            ]]
        ]);



        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*$query->andFilterWhere([
            'id' => $this->id,
            'cantidad_sillas' => $this->cantidad_sillas,
            'usuarioid' => $this->usuarioid,
            'licenciaid' => $this->licenciaid,
        ]);*/

        $query->andFilterWhere(['licencia.estado' => $this->estado])
            ->andFilterWhere(['like', 'licencia_spec.nombre', $this->espec_nombre])
            ->andFilterWhere(['like', 'user.email', $this->propietario_Viejo]);
        /*$query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'hora_inicio', $this->hora_inicio])
            ->andFilterWhere(['like', 'hora_fin', $this->hora_fin])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'descripcion_corta', $this->descripcion_corta]);*/

        return $dataProvider;
    }
}
