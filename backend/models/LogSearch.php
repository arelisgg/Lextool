<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Log;

/**
 * LogSearch represents the model behind the search form of `app\models\Log`.
 */
class LogSearch extends Log
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_log', 'id_user'], 'integer'],
            [['usuario', 'ip', 'action', 'table', 'date', 'time', 'record', 'rolesName', 'rolesNameProject'], 'safe'],
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
        $query = Log::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => [
//                'pageSize' => 20,
//            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->distinct('id_log');

        $query->joinWith('user')
            ->innerJoin('auth_assignment', 'auth_assignment.user_id = "user"."id_user"')
            ->leftJoin('user_project', 'user_project.id_user = "user"."id_user"');

        $dataProvider->setSort([
            'attributes'=>[
                'ip',
                'action',
                'record',
                'table',
                'date',
                'time',
                'usuario'=>[
                    'asc'=>['user.username'=>SORT_ASC],
                    'desc'=>['user.username'=>SORT_DESC],
                ]
            ],
            'defaultOrder' => ['date'=>SORT_DESC, 'time' =>SORT_DESC]
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id_log' => $this->id_log,
            'id_user' => $this->id_user,
            'date' => $this->date,
            'time' => $this->time,
        ]);

        $query->andFilterWhere(['ilike', 'ip', $this->ip])
            ->andFilterWhere(['ilike', 'action', $this->action])
            ->andFilterWhere(['ilike', 'table', $this->table])
            ->andFilterWhere(['ilike', 'record', $this->record])
            ->andFilterWhere(['ilike', 'user.username', $this->usuario])
            ->andFilterWhere(['ilike', 'auth_assignment.item_name', $this->rolesName])
            ->andFilterWhere(['ilike', 'user_project.role', $this->rolesNameProject]);

        return $dataProvider;
    }
}
