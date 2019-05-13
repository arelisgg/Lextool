<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ComplementaryDoc;

/**
 * ComplementaryDocSearch represents the model behind the search form of `app\models\ComplementaryDoc`.
 */
class ComplementaryDocSearch extends ComplementaryDoc
{
    public $docTypes = [];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_complementary_doc', 'id_project', 'id_doc_type'], 'integer'],
            [['name', 'url', 'doc_name'], 'safe'],
            [['accepted'], 'boolean'],
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
        $query = ComplementaryDoc::find();

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

        foreach ($this->docTypes as $docType){
            $query->orFilterWhere([
                'complementary_doc.id_doc_type' => $docType->id_doc_type,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_complementary_doc' => $this->id_complementary_doc,
            'id_project' => $this->id_project,
            'id_doc_type' => $this->id_doc_type,
            'accepted' => $this->accepted,
        ]);

        $query->andFilterWhere(['ilike', 'complementary_doc.name', $this->name])
            ->andFilterWhere(['ilike', 'url', $this->url])
            ->andFilterWhere(['ilike', 'doc_type.name', $this->doc_name]);

        $dataProvider->setSort([
            'attributes'=>[
                'accepted',
                'url',
                'name',
                'doc_name'=>[
                    'asc'=>['doc_type.name'=>SORT_ASC],
                    'desc'=>['doc_type.name'=>SORT_DESC],
                ]
            ],
            //'defaultOrder' => ['usuario'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
