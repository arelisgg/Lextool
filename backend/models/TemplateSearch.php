<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Templates;

/**
 * TemplateSearch represents the model behind the search form of `backend\models\Templates`.
 */
class TemplateSearch extends Templates
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_template', 'id_project', 'id_template_type'], 'integer'],
            [['name','template_type'],'safe'],

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
        $query = \backend\models\Templates::find();

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

        if ($this->name != ''){
            $query->andWhere("template.name ilike '%".$this->name."%' OR template_type.name ilike '%".$this->name."%'");
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_template' => $this->id_template,
            'id_project' => $this->id_project,
            'id_template_type' => $this->id_template_type,
        ]);



        $dataProvider->setSort([
            'attributes'=>[
                'name',
                'template_type'=>[
                    'asc'=>['template_type.name'=>SORT_ASC],
                    'desc'=>['template_type.name'=>SORT_DESC],
                ],
            ],
        ]);

        return $dataProvider;
    }
}
