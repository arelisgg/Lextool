<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ElementType;

/**
 * ElementTypeSearch represents the model behind the search form of `backend\models\ElementType`.
 */
class ElementTypeSearch extends ElementType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_element_type', 'removed'], 'integer'],
            [['name'], 'safe'],
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
        $query = ElementType::find();

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
            'id_element_type' => $this->id_element_type,
            'removed' => false,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        $dataProvider->setSort([
            'attributes'=>[
                'name',
            ],
            'defaultOrder' => ['name'=>SORT_ASC]
        ]);

        return $dataProvider;
    }
}
