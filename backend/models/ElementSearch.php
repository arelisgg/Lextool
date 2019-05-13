<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Element;

/**
 * ElementSearch represents the model behind the search form of `backend\models\Element`.
 */
class ElementSearch extends Element
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_element', 'id_project', 'id_element_type', 'font_size'], 'integer'],
            [['property', 'font', 'font_weight', 'font_style', 'text_decoration', 'color', 'background', 'element_name', 'text_transform'], 'safe'],
            [['visibility'], 'boolean'],
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
        $query = Element::find();

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
            'id_element' => $this->id_element,
            'id_project' => $this->id_project,
            'id_element_type' => $this->id_element_type,
            'visibility' => $this->visibility,
            'font_size' => $this->font_size,
        ]);

        $query->andFilterWhere(['ilike', 'property', $this->property])
            ->andFilterWhere(['ilike', 'font', $this->font])
            ->andFilterWhere(['ilike', 'font_weight', $this->font_weight])
            ->andFilterWhere(['ilike', 'font_style', $this->font_style])
            ->andFilterWhere(['ilike', 'text_decoration', $this->text_decoration])
            ->andFilterWhere(['ilike', 'color', $this->color])
            ->andFilterWhere(['ilike', 'background', $this->background])
            ->andFilterWhere(['ilike', 'text_transform', $this->text_transform])
            ->andFilterWhere(['ilike', 'element_type.name', $this->element_name])
        ;

        $dataProvider->setSort([
            'attributes'=>[
                'property',
                'visibility',
                'element_name'=>[
                    'asc'=>['element_type.name'=>SORT_ASC],
                    'desc'=>['element_type.name'=>SORT_DESC],
                ],
                'font', 'font_weight', 'font_style', 'text_decoration', 'color', 'background', 'font_size', 'text_transform',
            ],
        ]);

        return $dataProvider;
    }
}
