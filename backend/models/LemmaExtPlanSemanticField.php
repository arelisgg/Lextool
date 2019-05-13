<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lemma_ext_plan_semantic_field".
 *
 * @property int $id_lemma_ext_plan
 * @property int $id_semantic_field
 *
 * @property LemmaExtPlan $lemmaExtPlan
 * @property SemanticField $semanticField
 */
class LemmaExtPlanSemanticField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lemma_ext_plan_semantic_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lemma_ext_plan', 'id_semantic_field'], 'required'],
            [['id_lemma_ext_plan', 'id_semantic_field'], 'default', 'value' => null],
            [['id_lemma_ext_plan', 'id_semantic_field'], 'integer'],
            [['id_lemma_ext_plan', 'id_semantic_field'], 'unique', 'targetAttribute' => ['id_lemma_ext_plan', 'id_semantic_field']],
            [['id_lemma_ext_plan'], 'exist', 'skipOnError' => true, 'targetClass' => LemmaExtPlan::className(), 'targetAttribute' => ['id_lemma_ext_plan' => 'id_lemma_ext_plan']],
            [['id_semantic_field'], 'exist', 'skipOnError' => true, 'targetClass' => SemanticField::className(), 'targetAttribute' => ['id_semantic_field' => 'id_semantic_field']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_lemma_ext_plan' => 'Id Lemma Ext Plan',
            'id_semantic_field' => 'Id Semantic Field',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaExtPlan()
    {
        return $this->hasOne(LemmaExtPlan::className(), ['id_lemma_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemanticField()
    {
        return $this->hasOne(SemanticField::className(), ['id_semantic_field' => 'id_semantic_field']);
    }
}
