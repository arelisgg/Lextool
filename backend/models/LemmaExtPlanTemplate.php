<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lemma_ext_plan_template".
 *
 * @property int $id_template
 * @property int $id_ext_plan
 *
 * @property LemmaExtPlan $extPlan
 * @property Templates $template
 */
class LemmaExtPlanTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lemma_ext_plan_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_template', 'id_ext_plan'], 'required'],
            [['id_template', 'id_ext_plan'], 'default', 'value' => null],
            [['id_template', 'id_ext_plan'], 'integer'],
            [['id_template', 'id_ext_plan'], 'unique', 'targetAttribute' => ['id_template', 'id_ext_plan']],
            [['id_ext_plan'], 'exist', 'skipOnError' => true, 'targetClass' => LemmaExtPlan::className(), 'targetAttribute' => ['id_ext_plan' => 'id_lemma_ext_plan']],
            [['id_template'], 'exist', 'skipOnError' => true, 'targetClass' => Templates::className(), 'targetAttribute' => ['id_template' => 'id_template']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_template' => 'Id template',
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
    public function getTemplate()
    {
        return $this->hasOne(Templates::className(), ['id_template' => 'id_template']);
    }
    public function getExtPlanID($id)
    {
        return $this->id_ext_plan;
    }
}
