<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "redaction_plan_submodel".
 *
 * @property int $id_redaction_plan
 * @property int $id_sub_model
 *
 * @property RedactionPlan $redactionPlan
 * @property SubModel $subModel
 */
class RedactionPlanSubmodel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'redaction_plan_submodel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_redaction_plan', 'id_sub_model'], 'required'],
            [['id_redaction_plan', 'id_sub_model'], 'default', 'value' => null],
            [['id_redaction_plan', 'id_sub_model'], 'integer'],
            [['id_redaction_plan', 'id_sub_model'], 'unique', 'targetAttribute' => ['id_redaction_plan', 'id_sub_model']],
            [['id_redaction_plan'], 'exist', 'skipOnError' => true, 'targetClass' => RedactionPlan::className(), 'targetAttribute' => ['id_redaction_plan' => 'id_redaction_plan']],
            [['id_sub_model'], 'exist', 'skipOnError' => true, 'targetClass' => SubModel::className(), 'targetAttribute' => ['id_sub_model' => 'id_sub_model']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_redaction_plan' => 'Id Redaction Plan',
            'id_sub_model' => 'Id Sub Model',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedactionPlan()
    {
        return $this->hasOne(RedactionPlan::className(), ['id_redaction_plan' => 'id_redaction_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModel()
    {
        return $this->hasOne(SubModel::className(), ['id_sub_model' => 'id_sub_model']);
    }
}
