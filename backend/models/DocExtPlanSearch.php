<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DocExtPlan;

/**
 * DocExtPlanSearch represents the model behind the search form of `app\models\DocExtPlan`.
 */
class DocExtPlanSearch extends DocExtPlan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_doc_ext_plan', 'id_project', 'id_source', 'id_doc_type', 'id_user'], 'integer'],
            [['start_date', 'end_date', 'usuario', 'doc_name',  'source_name', 'late_search'], 'safe'],
            [['finished'], 'boolean'],
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
        $query = DocExtPlan::find();

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

        $query->joinWith('user')
            ->joinWith('docType')
            ->joinWith('source');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_doc_ext_plan' => $this->id_doc_ext_plan,
            'doc_ext_plan.id_project' => $this->id_project,
            'id_user' => $this->id_user,
            'id_source' => $this->id_source,
            'id_doc_type' => $this->id_doc_type,
            'finished' => $this->finished,
        ]);

        $query
            ->andFilterWhere(['ilike', 'doc_type.name', $this->doc_name])
            ->andFilterWhere(['ilike', 'source.name', $this->source_name])
            ->andFilterWhere(['ilike', "to_char(start_date, 'yyyy-mm-dd')", $this->start_date])
            ->andFilterWhere(['ilike', "to_char(end_date, 'yyyy-mm-dd')", $this->end_date])
            ->andFilterWhere(['ilike', 'user.full_name', $this->usuario]);

        if ($this->late_search == "Sí")
            $query->andWhere("finished = false AND end_date < current_date");
        else if ($this->late_search == "No")
            $query->andWhere("finished = true OR (finished = false AND end_date >= current_date)");

        $dataProvider->setSort([
            'attributes'=>[
                'finished',
                'start_date',
                'end_date',
                'semantic_field',
                'usuario'=>[
                    'asc'=>['user.full_name'=>SORT_ASC],
                    'desc'=>['user.full_name'=>SORT_DESC],
                ],
                'doc_name'=>[
                    'asc'=>['doc_type.name'=>SORT_ASC],
                    'desc'=>['doc_type.name'=>SORT_DESC],
                ],
                'source_name'=>[
                    'asc'=>['source.name'=>SORT_ASC],
                    'desc'=>['source.name'=>SORT_DESC],
                ]
            ],
            //'defaultOrder' => ['usuario'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
