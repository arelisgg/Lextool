<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RevisionPlan;

/**
 * RevisionPlanSearch represents the model behind the search form of `backend\models\RevisionPlan`.
 */
class RevisionPlanSearch extends RevisionPlan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_revision_plan', 'id_user', 'id_project'], 'integer'],
            [['edition', 'finished'], 'boolean'],
            [['usuario', 'start_date', 'end_date', 'late_search', 'type', 'letters_name',], 'safe'],
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
        $query = RevisionPlan::find();

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

        $query->distinct('id_revision_plan');

        $query->joinWith('user')->joinWith('letters');



        // grid filtering conditions
        $query->andFilterWhere([
            'id_revision_plan' => $this->id_revision_plan,
            'revision_plan.id_user' => $this->id_user,
            'revision_plan.edition' => $this->edition,
            'revision_plan.id_project' => $this->id_project,
            'revision_plan.finished' => $this->finished,
        ]);

        $query
            ->andFilterWhere(['ilike', "to_char(revision_plan.start_date, 'yyyy-mm-dd')", $this->start_date])
            ->andFilterWhere(['ilike', "to_char(revision_plan.end_date, 'yyyy-mm-dd')", $this->end_date])
            ->andFilterWhere(['ilike', "type", $this->type])
            ->andFilterWhere(['ilike', 'letter.letter', $this->letters_name])
            ->andFilterWhere(['ilike', 'user.full_name', $this->usuario]);

        if ($this->late_search == "Sí")
            $query->andWhere("revision_plan.finished = false AND revision_plan.end_date < current_date");
        else if ($this->late_search == "No")
            $query->andWhere("revision_plan.finished = true OR (revision_plan.finished = false AND revision_plan.end_date >= current_date)");

        return $dataProvider;
    }
}
