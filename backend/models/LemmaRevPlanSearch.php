<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\LemmaRevPlan;

/**
 * LemmaRevPlanSearch represents the model behind the search form of `backend\models\LemmaRevPlan`.
 */
class LemmaRevPlanSearch extends LemmaRevPlan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rev_plan', 'id_user', 'id_ext_plan', 'id_project'], 'integer'],
            [['usuario', 'lemma_ext_plan', 'start_date', 'end_date', 'late_search'], 'safe'],
            [['edition', 'finished'], 'boolean'],
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
        $query = LemmaRevPlan::find();

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
        $query->distinct('lemma_rev_plan.id_rev_plan');

        if ($this->lemma_ext_plan != ''){
            $query
                ->joinWith('extPlan')
                ->innerJoin('lemma_ext_plan_letter', 'lemma_ext_plan_letter.id_ext_plan = lemma_ext_plan.id_lemma_ext_plan')
                ->innerJoin('letter', 'lemma_ext_plan_letter.id_letter = letter.id_letter')

                ->innerJoin('user as usuario', 'lemma_ext_plan.id_user = "usuario".id_user');
            $query->andWhere("letter ilike '%".$this->lemma_ext_plan."%' OR usuario.full_name ilike '%".$this->lemma_ext_plan."%'");
            //$this->usuario ='';
        }

        if ($this->usuario != ''){
            $query->joinWith('user');
            $query->andFilterWhere(['ilike', 'user.full_name', $this->usuario]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'lemma_rev_plan.id_rev_plan' => $this->id_rev_plan,
            'lemma_rev_plan.id_user' => $this->id_user,
            'lemma_rev_plan.id_ext_plan' => $this->id_ext_plan,
            'lemma_rev_plan.edition' => $this->edition,
            'lemma_rev_plan.id_project' => $this->id_project,
            'lemma_rev_plan.finished' => $this->finished,
        ]);

        $query
            ->andFilterWhere(['ilike', "to_char(lemma_rev_plan.start_date, 'yyyy-mm-dd')", $this->start_date])
            ->andFilterWhere(['ilike', "to_char(lemma_rev_plan.end_date, 'yyyy-mm-dd')", $this->end_date]);

        if ($this->late_search == "Sí")
            $query->andWhere("lemma_rev_plan.finished = false AND lemma_rev_plan.end_date < current_date");
        else if ($this->late_search == "No")
            $query->andWhere("lemma_rev_plan.finished = true OR (lemma_rev_plan.finished = false AND lemma_rev_plan.end_date >= current_date)");

        /*$dataProvider->setSort([
            'attributes'=>[
                'lemma_ext_plan'=>[
                    'asc'=>['lemma_ext_plan.semantic_field'=>SORT_ASC],
                    'desc'=>['lemma_ext_plan.semantic_field'=>SORT_DESC],
                ],
                'edition',
                'usuario'=>[
                    'asc'=>['user.username'=>SORT_ASC],
                    'desc'=>['user.username'=>SORT_DESC],
                ]
            ],
            'defaultOrder' => ['usuario'=>SORT_ASC]
        ]);*/

        return $dataProvider;
    }
}
