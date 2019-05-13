<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ReviewCriteria;

/**
 * ReviewCriteriaSearch represents the model behind the search form of `backend\models\ReviewCriteria`.
 */
class ReviewCriteriaSearch extends ReviewCriteria
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_review_criteria'], 'integer'],
            [['criteria'], 'safe'],
            [['removed'], 'boolean'],
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
        $query = ReviewCriteria::find();

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
            'id_review_criteria' => $this->id_review_criteria,
            'removed' => false,
        ]);

        $query->andFilterWhere(['ilike', 'criteria', $this->criteria]);

        return $dataProvider;
    }
}
