<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "illustration_plan_letter".
 *
 * @property int $id_illustration_plan
 * @property int $id_letter
 *
 * @property IllustrationPlan $illustrationPlan
 * @property Letter $letter
 */
class IllustrationPlanLetter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'illustration_plan_letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_illustration_plan', 'id_letter'], 'required'],
            [['id_illustration_plan', 'id_letter'], 'default', 'value' => null],
            [['id_illustration_plan', 'id_letter'], 'integer'],
            [['id_illustration_plan', 'id_letter'], 'unique', 'targetAttribute' => ['id_illustration_plan', 'id_letter']],
            [['id_illustration_plan'], 'exist', 'skipOnError' => true, 'targetClass' => IllustrationPlan::className(), 'targetAttribute' => ['id_illustration_plan' => 'id_illustration_plan']],
            [['id_letter'], 'exist', 'skipOnError' => true, 'targetClass' => Letter::className(), 'targetAttribute' => ['id_letter' => 'id_letter']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_illustration_plan' => 'Id Illustration Plan',
            'id_letter' => 'Id Letter',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationPlan()
    {
        return $this->hasOne(IllustrationPlan::className(), ['id_illustration_plan' => 'id_illustration_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetter()
    {
        return $this->hasOne(Letter::className(), ['id_letter' => 'id_letter']);
    }
}
