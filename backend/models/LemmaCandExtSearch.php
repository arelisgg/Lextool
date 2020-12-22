<?php
/**
 * Created by PhpStorm.
 * User: Leo
 * Date: 20/12/2020
 * Time: 20:15
 */

namespace backend\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * LemmaCandExtSearch represents the model behind the search form of `app\models\LemmaCandExt.
 */
class LemmaCandExtSearch extends LemmaCandExt
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'id_lemma', 'id_element', 'id_sub_element', 'order', 'number'], 'integer'],
            [[ 'description'], 'string'],
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
        $query = LemmaCandExt::find();

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
        $query->distinct('id_lemma_cand_ext');



        // grid filtering conditions
        $query->andFilterWhere([
            'id_lemma_cand_ext' => $this->id_lemma_cand_ext ,
            'id_lemma' => $this->id_lemma,
            'id_element' =>$this->id_element ,
            'id_sub_element' => $this->id_sub_element,
            'order' => $this->order,
            'number' => $this->number,

        ]);

        $query->andFilterWhere([
            'ilike', 'description', $this->description
        ]);

        return $dataProvider;
    }

}