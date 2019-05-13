<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Separator;

/**
 * SeparatorSearch represents the model behind the search form of `backend\models\Separator`.
 */
class SeparatorSearch extends Separator
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_separator', 'id_project'], 'integer'],
            [['type', 'representation', 'scope'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Separator::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_separator' => $this->id_separator,
            'id_project' => $this->id_project,
        ]);

        $query->andFilterWhere(['ilike', 'type', $this->type])
            ->andFilterWhere(['ilike', 'representation', $this->representation])
            ->andFilterWhere(['ilike', 'scope', $this->scope]);

        return $dataProvider;
    }
}
