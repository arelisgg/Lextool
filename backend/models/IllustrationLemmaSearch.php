<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IllustrationLemma;

/**
 * IllustrationLemmaSearch represents the model behind the search form of `backend\models\IllustrationLemma`.
 */
class IllustrationLemmaSearch extends IllustrationLemma
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_illustration_lemma', 'id_illustration', 'id_lemma', 'id_illustration_plan', 'id_letter'], 'integer'],
            [['lemma_search', 'illustration_search'], 'safe'],
            [['reviewed',], 'boolean'],
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
        $query = IllustrationLemma::find();

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

        $query->joinWith('lemma')
            //->joinWith('illustration')
            //->joinWith('illustrationPlan')
            //->innerJoin('illustration_plan_letter', 'illustration_plan_letter.id_illustration_plan = illustration_plan.id_illustration_plan')
            //->innerJoin('letter', 'lemma.id_letter = letter.id_letter')
        ;

        $query->andFilterWhere([
            'id_illustration_lemma' => $this->id_illustration_lemma,
            'illustration_lemma.id_illustration' => $this->id_illustration,
            'illustration_lemma.id_lemma' => $this->id_lemma,
            'illustration_lemma.id_illustration_plan' => $this->id_illustration_plan,
            'illustration_lemma.reviewed' => $this->reviewed,
            'lemma.id_letter' => $this->id_letter,
        ]);

        $query->andFilterWhere(['ilike', 'extracted_lemma', $this->lemma_search])
            //->andFilterWhere(['=', 'lemma.letter', $this->letter])
            //->andFilterWhere(['ilike', 'name', $this->illustration_search])
        ;

        $dataProvider->setSort([
            'attributes'=>[
                'lemma_search'=>[
                    'asc'=>['lemma.extracted_lemma'=>SORT_ASC],
                    'desc'=>['lemma.extracted_lemma'=>SORT_DESC],
                ],
                /*'illutration_search'=>[
                    'asc'=>['illustration.name'=>SORT_ASC],
                    'desc'=>['illustration.name'=>SORT_DESC],
                ],*/
            ],
            'defaultOrder' => ['lemma_search'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
