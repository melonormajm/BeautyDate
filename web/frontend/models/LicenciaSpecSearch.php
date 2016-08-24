<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LicenciaSpec;

/**
 * LicenciaSpecSearch represents the model behind the search form about `common\models\LicenciaSpec`.
 */
class LicenciaSpecSearch extends LicenciaSpec
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'duracion'], 'integer'],
            [['precio'], 'number'],
            [['tipo_duracion'], 'safe'],
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
        $query = LicenciaSpec::find();

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
            'precio' => $this->precio,
            'duracion' => $this->duracion,
        ]);

        $query->andFilterWhere(['like', 'tipo_duracion', $this->tipo_duracion]);

        return $dataProvider;
    }
}
