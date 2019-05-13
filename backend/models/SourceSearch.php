<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Source;

/**
 * SourceSearch represents the model behind the search form of `app\models\Source`.
 */
class SourceSearch extends Source
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_source', 'id_project'], 'integer'],
            [['name', 'url'], 'safe'],
            [['editable'], 'boolean'],
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
        $query = Source::find();

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
            'id_source' => $this->id_source,
            'id_project' => $this->id_project,
            'editable' => $this->editable,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'url', $this->url]);

        $dataProvider->setSort([
            'attributes'=>[
                'name',
                'url',
                'editable',
            ],
            'defaultOrder' => ['name'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
