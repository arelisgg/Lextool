<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SubModel;

/**
 * SubModelSearch represents the model behind the search form of `backend\models\SubModel`.
 */
class SubModelSearch extends SubModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_sub_model', 'id_project', 'order'], 'integer'],
            [['name'],'safe'],
            [['repeat','required'], 'boolean'],
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
        $query = SubModel::find();

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
        $query->joinWith('elements')
            ->innerJoin('element_type', 'element_type.id_element_type = element.id_element_type');

        if ($this->name != ''){
            $query->andWhere("sub_model.name ilike '%".$this->name."%' OR element_type.name ilike '%".$this->name."%'");
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_sub_model' => $this->id_sub_model,
            'sub_model.id_project' => $this->id_project,
            'repeat' => $this->repeat,
            'required' => $this->required,
            'order' => $this->order,
        ]);



        $dataProvider->setSort([
            'attributes'=>[
                'name',
                'repeat',
                'required',
            ],
        ]);

        return $dataProvider;
    }
}
