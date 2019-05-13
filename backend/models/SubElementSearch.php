<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SubElement;

/**
 * SubElementSearch represents the model behind the search form of `backend\models\SubElement`.
 */
class SubElementSearch extends SubElement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_sub_element', 'id_element', 'id_element_sub_type', 'font_size'], 'integer'],
            [['visibility'], 'boolean'],
            [['font', 'font_weight', 'font_style', 'text_decoration', 'color', 'background', 'text_transform'], 'safe'],
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
        $query = SubElement::find();

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
            'id_sub_element' => $this->id_sub_element,
            'id_element' => $this->id_element,
            'id_element_sub_type' => $this->id_element_sub_type,
            'visibility' => $this->visibility,
            'font_size' => $this->font_size,
        ]);

        $query->andFilterWhere(['ilike', 'font', $this->font])
            ->andFilterWhere(['ilike', 'font_weight', $this->font_weight])
            ->andFilterWhere(['ilike', 'font_style', $this->font_style])
            ->andFilterWhere(['ilike', 'text_decoration', $this->text_decoration])
            ->andFilterWhere(['ilike', 'color', $this->color])
            ->andFilterWhere(['ilike', 'background', $this->background])
            ->andFilterWhere(['ilike', 'text_transform', $this->text_transform]);




        return $dataProvider;


    }
}
