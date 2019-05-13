<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Lemma;

/**
 * LemmaSearch represents the model behind the search form of `backend\models\Lemma`.
 */
class LemmaSearch extends Lemma
{

    public $letters = [];
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lemma', 'id_project', 'id_letter', 'id_source', 'id_user', 'id_lemma_ext_plan'], 'integer'],
            [['extracted_lemma', 'original_lemma', 'structure', 'substructure', 'original_text',
                'remark', 'letter_name', 'usuario', 'source_name', 'lemma'], 'safe'],
            [['agree', 'finished', 'lemario', 'homonym', 'lexArtFinished', 'lexArtReviewed', 'extReviewed'], 'boolean'],
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
        $query = Lemma::find();

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

        $query->joinWith('letter')
            ->joinWith('source')
            ->joinWith('user')
            ->joinWith('lexArticle');

        foreach ($this->letters as $letter){
            $query->orFilterWhere([
                'lemma.id_letter' => $letter->id_letter,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_lemma' => $this->id_lemma,
            'lemma.id_project' => $this->id_project,
            'lemma.id_letter' => $this->id_letter,
            'lemma.id_source' => $this->id_source,
            'lemma.id_user' => $this->id_user,
            'lemma.agree' => $this->agree,
            'lemma.finished' => $this->finished,
            'lemma.lemario' => $this->lemario,
            'lemma.homonym' => $this->homonym,
            'lemma.id_lemma_ext_plan' => $this->id_lemma_ext_plan,
            'lex_article.finished' => $this->lexArtFinished,
            'lex_article.reviewed' => $this->lexArtReviewed,
        ]);

        $query->andFilterWhere([
            'lemma.agree' => $this->extReviewed,
        ]);

        $query->andFilterWhere(['ilike', 'extracted_lemma', $this->extracted_lemma])
            ->andFilterWhere(['ilike', 'extracted_lemma', $this->lemma])
            ->andFilterWhere(['ilike', 'original_lemma', $this->original_lemma])
            ->andFilterWhere(['ilike', 'structure', $this->structure])
            ->andFilterWhere(['ilike', 'substructure', $this->substructure])
            ->andFilterWhere(['ilike', 'original_text', $this->original_text])
            ->andFilterWhere(['ilike', 'user.full_name', $this->usuario])
            ->andFilterWhere(['ilike', 'letter.letter', $this->letter_name])
            ->andFilterWhere(['ilike', 'source.name', $this->source_name]);



        $dataProvider->setSort([
            'attributes'=>[

                'letter_name'=>[
                    'asc'=>['letter.letter'=>SORT_ASC],
                    'desc'=>['letter.letter'=>SORT_DESC],
                ],
                'id_letter'=>[
                    'asc'=>['letter.letter'=>SORT_ASC],
                    'desc'=>['letter.letter'=>SORT_DESC],
                ],
                'lexArtReviewed'=>[
                    'asc'=>['lex_article.reviewed'=>SORT_ASC],
                    'desc'=>['lex_article.reviewed'=>SORT_DESC],
                ],
                'lexArtFinished'=>[
                    'asc'=>['lex_article.finished'=>SORT_ASC],
                    'desc'=>['lex_article.finished'=>SORT_DESC],
                ],
                'lemma'=>[
                    'asc'=>['extracted_lemma'=>SORT_ASC],
                    'desc'=>['extracted_lemma'=>SORT_DESC],
                ],
                'extReviewed'=>[
                    'asc'=>['agree'=>SORT_ASC],
                    'desc'=>['agree'=>SORT_DESC],
                ],
                'extracted_lemma',
                'usuario'=>[
                    'asc'=>['user.full_name'=>SORT_ASC],
                    'desc'=>['user.full_name'=>SORT_DESC],
                ],
                'source_name'=>[
                    'asc'=>['source.name'=>SORT_ASC],
                    'desc'=>['source.name'=>SORT_DESC],
                ],
                'homonym',
                'original_lemma',
                'structure',
                'substructure',
                'agree',
                'lemario',
                'finished',
            ],
            'defaultOrder' => ['letter_name'=>SORT_ASC, 'id_letter'=>SORT_ASC, 'extracted_lemma'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
