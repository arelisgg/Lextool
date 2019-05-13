<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IllustrationPlan;

/**
 * IllustrationPlanSearch represents the model behind the search form of `backend\models\IllustrationPlan`.
 */
class IllustrationPlanSearch extends IllustrationPlan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_illustration_plan', 'id_project', 'id_user'], 'integer'],
            [['usuario', 'start_date', 'end_date', 'type', 'late_search', 'letters_name', 'documents_name'], 'safe'],
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
        $query = IllustrationPlan::find();

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
        $query->distinct('id_illustration_plan');

        $query
            ->joinWith('user')
            ->joinWith('letters')
            ->joinWith('documents')
            ->leftJoin('doc_type', 'doc_type.id_doc_type = complementary_doc.id_doc_type');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_illustration_plan' => $this->id_illustration_plan,
            'illustration_plan.id_project' => $this->id_project,
            'illustration_plan.id_user' => $this->id_user,
            'finished' => $this->finished,
        ]);

        $query
            ->andFilterWhere(['ilike', "to_char(start_date, 'yyyy-mm-dd')", $this->start_date])
            ->andFilterWhere(['ilike', "to_char(end_date, 'yyyy-mm-dd')", $this->end_date])
            ->andFilterWhere(['ilike', 'user.full_name', $this->usuario])
            ->andFilterWhere(['ilike', 'letter.letter', $this->letters_name])
            ->andFilterWhere(['ilike', 'doc_type.name', $this->documents_name])
            ->andFilterWhere(['ilike', 'type', $this->type]);

        if ($this->late_search == "Sí")
            $query->andWhere("finished = false AND end_date < current_date");
        else if ($this->late_search == "No")
            $query->andWhere("finished = true OR (finished = false AND end_date >= current_date)");

        $dataProvider->setSort([
            'attributes'=>[
                'finished',
                'start_date',
                'end_date',
                'type',
                /*'usuario'=>[
                    'asc'=>['user.full_name'=>SORT_ASC],
                    'desc'=>['user.full_name'=>SORT_DESC],
                ]*/
            ],
            //'defaultOrder' => ['usuario'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
