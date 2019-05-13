<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SourceLetter;

/**
 * SourceLetterSearch represents the model behind the search form of `app\models\SourceLetter`.
 */
class SourceLetterSearch extends SourceLetter
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_letter', 'id_source'], 'integer'],
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
        $query = SourceLetter::find();

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
            'id_letter' => $this->id_letter,
            'id_source' => $this->id_source,
        ]);

        return $dataProvider;
    }
}
