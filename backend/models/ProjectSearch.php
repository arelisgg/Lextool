<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Project;

/**
 * ProjectSearch represents the model behind the search form of `app\models\Project`.
 */
class ProjectSearch extends Project
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_dictionary_type'], 'integer'],
            [['name', 'description', 'status', 'plant_file', 'start_date', 'end_date', 'dictionary_type'], 'safe'],
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
        $query = Project::find();

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

        $query->joinWith("dictionaryType");

        $dataProvider->setSort([
            'attributes'=>[
                'name',
                'dictionary_type'=>[
                    'asc'=>['dictionary_type.type'=>SORT_ASC],
                    'desc'=>['dictionary_type.type'=>SORT_DESC],
                ],
                'status',
                'plant_file',
                'start_date',
                'end_date'
            ],
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id_project' => $this->id_project,
            'id_dictionary_type' => $this->id_dictionary_type,
        ]);


        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'status', $this->status])
            ->andFilterWhere(['ilike', 'plant_file', $this->plant_file])
            ->andFilterWhere(['ilike', "to_char(start_date, 'yyyy-mm-dd')", $this->start_date])
            ->andFilterWhere(['ilike', "to_char(end_date, 'yyyy-mm-dd')", $this->end_date])
            ->andFilterWhere(['ilike', 'dictionary_type.type', $this->dictionary_type]);

        return $dataProvider;
    }
}
