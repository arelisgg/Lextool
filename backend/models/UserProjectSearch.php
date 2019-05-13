<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UserProject;

/**
 * UserProjectSearch represents the model behind the search form of `app\models\UserProject`.
 */
class UserProjectSearch extends UserProject
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_user'], 'integer'],
            [['usuario', 'role'], 'safe'],
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
        $query = UserProject::find();

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
                'role',
                'usuario'=>[
                    'asc'=>['user.username'=>SORT_ASC],
                    'desc'=>['user.username'=>SORT_DESC],
                ]
            ],
            'defaultOrder' => ['usuario'=>SORT_ASC]
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id_project' => $this->id_project,
            'id_user' => $this->id_user,
        ]);

        $query->andFilterWhere(['ilike', 'role', $this->role])
            ->andFilterWhere(['ilike', 'user.full_name', $this->usuario]);

        return $dataProvider;
    }
}
