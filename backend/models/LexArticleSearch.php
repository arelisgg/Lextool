<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\LexArticle;

/**
 * LexArticleSearch represents the model behind the search form of `backend\models\LexArticle`.
 */
class LexArticleSearch extends LexArticle
{
    public $letters = [];
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lex_article', 'id_lemma', 'id_project'], 'integer'],
            [['article', 'letter', 'lemma_search'], 'safe'],
            [['finished', 'reviewed'], 'boolean'],
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
        $query = LexArticle::find();

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

        $query->joinWith('lemma')->innerJoin('letter', 'letter.id_letter = lemma.id_letter');

        foreach ($this->letters as $letter){
            $query->orFilterWhere([
                'lemma.id_letter' => $letter->id_letter,
            ]);
        }
        //$query->andFilterWhere(['!=', 'lex_article.id_lemma', null]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id_lex_article' => $this->id_lex_article,
            'id_lemma' => $this->id_lemma,
            'finished' => $this->finished,
            'reviewed' => $this->reviewed,
            'lemma.id_letter' => $this->letter,
            'lemma.id_project' => $this->id_project,
        ]);

        $query->andFilterWhere(['ilike', 'article', $this->article])
            ->andFilterWhere(['ilike', 'lemma.extracted_lemma', $this->lemma_search]);

        $dataProvider->setSort([
            'attributes'=>[

                'letter'=>[
                    'asc'=>['letter.letter'=>SORT_ASC],
                    'desc'=>['letter.letter'=>SORT_DESC],
                ],
                'lemma_search'=>[
                    'asc'=>['lemma.extracted_lemma'=>SORT_ASC],
                    'desc'=>['lemma.extracted_lemma'=>SORT_DESC],
                ],

            ],
            'defaultOrder' => ['letter'=>SORT_ASC,]
        ]);

        return $dataProvider;
    }
}
