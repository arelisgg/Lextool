<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lemma_ext_plan_source".
 *
 * @property int $id_source
 * @property int $id_ext_plan
 *
 * @property LemmaExtPlan $extPlan
 * @property Source $source
 */
class LemmaExtPlanSource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lemma_ext_plan_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_source', 'id_ext_plan'], 'required'],
            [['id_source', 'id_ext_plan'], 'default', 'value' => null],
            [['id_source', 'id_ext_plan'], 'integer'],
            [['id_source', 'id_ext_plan'], 'unique', 'targetAttribute' => ['id_source', 'id_ext_plan']],
            [['id_ext_plan'], 'exist', 'skipOnError' => true, 'targetClass' => LemmaExtPlan::className(), 'targetAttribute' => ['id_ext_plan' => 'id_lemma_ext_plan']],
            [['id_source'], 'exist', 'skipOnError' => true, 'targetClass' => Source::className(), 'targetAttribute' => ['id_source' => 'id_source']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_source' => 'Id Source',
            'id_ext_plan' => 'Id Ext Plan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtPlan()
    {
        return $this->hasOne(LemmaExtPlan::className(), ['id_lemma_ext_plan' => 'id_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Source::className(), ['id_source' => 'id_source']);
    }
}
