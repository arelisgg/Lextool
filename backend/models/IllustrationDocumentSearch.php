<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\IllustrationDocument;

/**
 * IllustrationDocumentSearch represents the model behind the search form of `backend\models\IllustrationDocument`.
 */
class IllustrationDocumentSearch extends IllustrationDocument
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_illustration_document', 'id_illustration', 'id_document', 'id_illustration_plan'], 'integer'],
            [['document_search',], 'safe'],
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
        $query = IllustrationDocument::find();

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

        $query->joinWith('document')
            ->innerJoin('doc_type', 'doc_type.id_doc_type = complementary_doc.id_doc_type');


        // grid filtering conditions
        $query->andFilterWhere([
            'id_illustration_document' => $this->id_illustration_document,
            'illustration_document.id_illustration' => $this->id_illustration,
            'illustration_document.id_document' => $this->id_document,
            'illustration_document.id_illustration_plan' => $this->id_illustration_plan,
            'illustration_document.reviewed' => $this->reviewed,
        ]);

        $query->andFilterWhere(['ilike', 'doc_type.name', $this->document_search])

        ;

        $dataProvider->setSort([
            'attributes'=>[
                'document_search'=>[
                    'asc'=>['doc_type.name'=>SORT_ASC],
                    'desc'=>['doc_type.name'=>SORT_DESC],
                ],
            ],
            'defaultOrder' => ['document_search'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
