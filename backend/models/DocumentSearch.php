<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Document;

/**
 * DocumentSearch represents the model behind the search form of `app\models\Document`.
 */
class DocumentSearch extends Document
{

    public $docTypes = [];
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_document', 'id_project', 'id_doc_type'], 'integer'],
            [['original_text', 'doc_type',], 'safe'],
            [['reviewed'], 'boolean'],
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
        $query = Document::find();

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

        $query->joinWith('docType');
        //$query->innerJoin('doc_type', 'doc_type.id_doc_type = document.id_doc_type');
        //$query->leftJoin('doc_type', 'doc_type.id_doc_type = document.id_doc_type');

        foreach ($this->docTypes as $docType){
            $query->orFilterWhere([
                'document.id_doc_type' => $docType->id_doc_type,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_document' => $this->id_document,
            'id_project' => $this->id_project,
            'document.id_doc_type' => $this->id_doc_type,
            'reviewed' => $this->reviewed,
        ]);

        $query->andFilterWhere(['ilike', 'original_text', $this->original_text])
            ->andFilterWhere(['ilike', 'doc_type.name', $this->doc_type]);

        $dataProvider->setSort([
            'attributes'=>[
                'reviewed',
                'doc_type'=>[
                    'asc'=>['doc_type.name'=>SORT_ASC],
                    'desc'=>['doc_type.name'=>SORT_DESC],
                ]
            ],
            //'defaultOrder' => ['usuario'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
