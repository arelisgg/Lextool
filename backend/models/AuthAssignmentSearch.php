<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AuthAssignment;

/**
 * AuthAssignmentSearch represents the model behind the search form of `app\models\AuthAssignment`.
 */
class AuthAssignmentSearch extends AuthAssignment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario', 'item_name'], 'safe'],
            [['user_id', 'created_at'], 'integer'],
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
        $query = AuthAssignment::find();

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
        $query->joinWith('user');

        $dataProvider->setSort([
            'attributes'=>[
                'item_name',
                'usuario'=>[
                    'asc'=>['user.username'=>SORT_ASC],
                    'desc'=>['user.username'=>SORT_DESC],
                ]
            ],
            'defaultOrder' => ['usuario'=>SORT_ASC]
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['ilike', 'item_name', $this->item_name])
            ->andFilterWhere(['ilike', 'user.username', $this->usuario]);

        return $dataProvider;
    }
}
