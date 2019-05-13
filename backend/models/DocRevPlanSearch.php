<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DocRevPlan;

/**
 * DocRevPlanSearch represents the model behind the search form of `backend\models\DocRevPlan`.
 */
class DocRevPlanSearch extends DocRevPlan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rev_plan', 'id_ext_doc_plan', 'id_user', 'id_project'], 'integer'],
            [['usuario', 'doc_ext_plan', 'start_date', 'end_date', 'late_search'], 'safe'],
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
        $query = DocRevPlan::find();

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

        if ($this->doc_ext_plan != ''){
            $query->joinWith('extDocPlan')
                ->leftJoin('source', 'source.id_source = doc_ext_plan.id_source')
                ->leftJoin('doc_type', 'doc_type.id_doc_type = doc_ext_plan.id_doc_type')
                ->leftJoin('user as usuario', '"usuario".id_user = doc_ext_plan.id_user');
            $query->andWhere("source.name ilike '%".$this->doc_ext_plan."%'
            OR doc_type.name ilike '%".$this->doc_ext_plan."%' OR usuario.full_name ilike '%".$this->doc_ext_plan."%'");
            //$this->usuario = '';
        }

        if ($this->usuario !=''){
            $query->joinWith('user');
            $query->andFilterWhere(['ilike', 'user.full_name', $this->usuario]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'doc_rev_plan.id_rev_plan' => $this->id_rev_plan,
            'doc_rev_plan.id_ext_doc_plan' => $this->id_ext_doc_plan,
            'doc_rev_plan.id_user' => $this->id_user,
            'doc_rev_plan.id_project' => $this->id_project,
            'doc_rev_plan.finished' => $this->finished,
        ]);

        $query
            ->andFilterWhere(['ilike', "to_char(doc_rev_plan.start_date, 'yyyy-mm-dd')", $this->start_date])
            ->andFilterWhere(['ilike', "to_char(doc_rev_plan.end_date, 'yyyy-mm-dd')", $this->end_date]);

        if ($this->late_search == "Sí")
            $query->andWhere("doc_rev_plan.finished = false AND doc_rev_plan.end_date < current_date");
        else if ($this->late_search == "No")
            $query->andWhere("doc_rev_plan.finished = true OR (doc_rev_plan.finished = false AND doc_rev_plan.end_date >= current_date)");

        $dataProvider->setSort([
            'attributes'=>[
                'finished',
                'start_date',
                'end_date',
                'usuario'=>[
                    'asc'=>['user.full_name'=>SORT_ASC],
                    'desc'=>['user.full_name'=>SORT_DESC],
                ],
                /*'doc_ext_plan'=>[
                    'asc'=>['usuario.full_name'=>SORT_ASC],
                    'desc'=>['usuario.full_name'=>SORT_DESC],
                ],*/
            ],
            //'defaultOrder' => ['usuario'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
