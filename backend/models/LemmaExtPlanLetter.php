<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lemma_ext_plan_letter".
 *
 * @property int $id_letter
 * @property int $id_ext_plan
 *
 * @property LemmaExtPlan $extPlan
 * @property Letter $letter
 */
class LemmaExtPlanLetter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lemma_ext_plan_letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_letter', 'id_ext_plan'], 'required'],
            [['id_letter', 'id_ext_plan'], 'default', 'value' => null],
            [['id_letter', 'id_ext_plan'], 'integer'],
            [['id_letter', 'id_ext_plan'], 'unique', 'targetAttribute' => ['id_letter', 'id_ext_plan']],
            [['id_ext_plan'], 'exist', 'skipOnError' => true, 'targetClass' => LemmaExtPlan::className(), 'targetAttribute' => ['id_ext_plan' => 'id_lemma_ext_plan']],
            [['id_letter'], 'exist', 'skipOnError' => true, 'targetClass' => Letter::className(), 'targetAttribute' => ['id_letter' => 'id_letter']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_letter' => 'Id Letter',
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
    public function getLetter()
    {
        return $this->hasOne(Letter::className(), ['id_letter' => 'id_letter']);
    }
}
