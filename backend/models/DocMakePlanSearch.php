<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DocMakePlan;

/**
 * DocMakePlanSearch represents the model behind the search form of `backend\models\DocMakePlan`.
 */
class DocMakePlanSearch extends DocMakePlan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_doc_make_plan', 'id_project', 'id_user'], 'integer'],
            [['start_date', 'end_date', 'usuario', 'late_search', 'docs_search', ], 'safe'],
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
        $query = DocMakePlan::find();

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

        $query->distinct('id_doc_make_plan');

        $query->joinWith('user')
            ->joinWith('docTypes');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_doc_make_plan' => $this->id_doc_make_plan,
            'doc_make_plan.id_project' => $this->id_project,
            'doc_make_plan.id_user' => $this->id_user,
            'finished' => $this->finished,
        ]);

        $query
            ->andFilterWhere(['ilike', "to_char(start_date, 'yyyy-mm-dd')", $this->start_date])
            ->andFilterWhere(['ilike', "to_char(end_date, 'yyyy-mm-dd')", $this->end_date])
            ->andFilterWhere(['ilike', 'user.full_name', $this->usuario])
            ->andFilterWhere(['ilike', 'doc_type.name', $this->docs_search]);

        if ($this->late_search == "Sí")
            $query->andWhere("finished = false AND end_date < current_date");
        else if ($this->late_search == "No")
            $query->andWhere("finished = true OR (finished = false AND end_date >= current_date)");

        $dataProvider->setSort([
            'attributes'=>[
                'finished',
                'start_date',
                'end_date',
                /*'usuario'=>[
                    'asc'=>['user.full_name'=>SORT_ASC],
                    'desc'=>['user.full_name'=>SORT_DESC],
                ],*/
                /*'docs_search'=>[
                    'asc'=>['doc_type.name'=>SORT_ASC],
                    'desc'=>['doc_type.name'=>SORT_DESC],
                ],*/
            ],
            //'defaultOrder' => ['usuario'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
