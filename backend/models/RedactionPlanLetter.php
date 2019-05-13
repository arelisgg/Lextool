<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "redaction_plan_letter".
 *
 * @property int $id_redaction_plan
 * @property int $id_letter
 *
 * @property Letter $letter
 * @property RedactionPlan $redactionPlan
 */
class RedactionPlanLetter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'redaction_plan_letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_redaction_plan', 'id_letter'], 'required'],
            [['id_redaction_plan', 'id_letter'], 'default', 'value' => null],
            [['id_redaction_plan', 'id_letter'], 'integer'],
            [['id_redaction_plan', 'id_letter'], 'unique', 'targetAttribute' => ['id_redaction_plan', 'id_letter']],
            [['id_letter'], 'exist', 'skipOnError' => true, 'targetClass' => Letter::className(), 'targetAttribute' => ['id_letter' => 'id_letter']],
            [['id_redaction_plan'], 'exist', 'skipOnError' => true, 'targetClass' => RedactionPlan::className(), 'targetAttribute' => ['id_redaction_plan' => 'id_redaction_plan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_redaction_plan' => 'Id Redaction Plan',
            'id_letter' => 'Id Letter',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetter()
    {
        return $this->hasOne(Letter::className(), ['id_letter' => 'id_letter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedactionPlan()
    {
        return $this->hasOne(RedactionPlan::className(), ['id_redaction_plan' => 'id_redaction_plan']);
    }
}
