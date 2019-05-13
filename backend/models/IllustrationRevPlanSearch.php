<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IllustrationRevPlan;

/**
 * IllustrationRevPlanSearch represents the model behind the search form of `backend\models\IllustrationRevPlan`.
 */
class IllustrationRevPlanSearch extends IllustrationRevPlan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_illustration_rev_plan', 'id_user', 'id_illustration_plan', 'id_project'], 'integer'],
            [['edition', 'finished'], 'boolean'],
            [['usuario', 'start_date', 'end_date', 'late_search', 'illustration_plan'], 'safe'],
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
        $query = IllustrationRevPlan::find();

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

        if ($this->illustration_plan != ''){
            $query
                ->joinWith('illustrationPlan')
                ->leftJoin('illustration_plan_letter', 'illustration_plan_letter.id_illustration_plan = illustration_plan.id_illustration_plan')
                ->leftJoin('letter', 'illustration_plan_letter.id_letter = letter.id_letter')
                ->leftJoin('illustration_plan_document', 'illustration_plan_document.id_illustration_plan = illustration_plan.id_illustration_plan')
                ->leftJoin('complementary_doc', 'illustration_plan_document.id_document = complementary_doc.id_complementary_doc')
                ->leftJoin('doc_type', 'doc_type.id_doc_type = complementary_doc.id_doc_type')

                ->leftJoin('user as usuario', 'illustration_plan.id_user = "usuario".id_user');

            $query->andWhere("letter ilike '%".$this->illustration_plan."%' 
                OR usuario.full_name ilike '%".$this->illustration_plan."%' 
                OR doc_type.name ilike '%".$this->illustration_plan."%' ");
            //$this->usuario ='';
        }

        if ($this->usuario !=''){
            $query->joinWith('user');
            $query->andFilterWhere(['ilike', 'user.full_name', $this->usuario]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_illustration_rev_plan' => $this->id_illustration_rev_plan,
            'illustration_rev_plan.id_user' => $this->id_user,
            'illustration_rev_plan.id_illustration_plan' => $this->id_illustration_plan,
            'illustration_rev_plan.edition' => $this->edition,
            'illustration_rev_plan.id_project' => $this->id_project,
            'illustration_rev_plan.finished' => $this->finished,
        ]);

        $query
            ->andFilterWhere(['ilike', "to_char(illustration_rev_plan.start_date, 'yyyy-mm-dd')", $this->start_date])
            ->andFilterWhere(['ilike', "to_char(illustration_rev_plan.end_date, 'yyyy-mm-dd')", $this->end_date]);

        if ($this->late_search == "Sí")
            $query->andWhere("illustration_rev_plan.finished = false AND illustration_rev_plan.end_date < current_date");
        else if ($this->late_search == "No")
            $query->andWhere("illustration_rev_plan.finished = true OR (illustration_rev_plan.finished = false AND illustration_rev_plan.end_date >= current_date)");

        return $dataProvider;
    }
}
