<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ElementSubType;

/**
 * ElementSubTypeSearch represents the model behind the search form of `backend\models\ElementSubType`.
 */
class ElementSubTypeSearch extends ElementSubType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_element_sub_type', 'id_element_type'], 'integer'],
            [['name', 'element_name'], 'safe'],
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
        $query = ElementSubType::find();

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

        $query->joinWith('elementType');



        // grid filtering conditions
        $query->andFilterWhere([
            'id_element_sub_type' => $this->id_element_sub_type,
            'id_element_type' => $this->id_element_type,
            'element_type.removed' => false,
            'element_sub_type.removed' => false,
        ]);

        $query
            ->andFilterWhere(['ilike', 'element_type.name', $this->element_name])
            ->andFilterWhere(['ilike', 'element_sub_type.name', $this->name]);

        $dataProvider->setSort([
            'attributes'=>[
                'element_name'=>[
                    'asc'=>['element_type.name'=>SORT_ASC],
                    'desc'=>['element_type.name'=>SORT_DESC],
                ],
                'name',
            ],
            'defaultOrder' => ['element_name'=>SORT_ASC, 'name'=>SORT_ASC]
        ]);


        return $dataProvider;
    }
}
