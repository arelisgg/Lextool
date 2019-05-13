<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "revision_plan_letter".
 *
 * @property int $id_revision_plan
 * @property int $id_letter
 *
 * @property Letter $letter
 * @property RevisionPlan $revisionPlan
 */
class RevisionPlanLetter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'revision_plan_letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_revision_plan', 'id_letter'], 'required'],
            [['id_revision_plan', 'id_letter'], 'default', 'value' => null],
            [['id_revision_plan', 'id_letter'], 'integer'],
            [['id_revision_plan', 'id_letter'], 'unique', 'targetAttribute' => ['id_revision_plan', 'id_letter']],
            [['id_letter'], 'exist', 'skipOnError' => true, 'targetClass' => Letter::className(), 'targetAttribute' => ['id_letter' => 'id_letter']],
            [['id_revision_plan'], 'exist', 'skipOnError' => true, 'targetClass' => RevisionPlan::className(), 'targetAttribute' => ['id_revision_plan' => 'id_revision_plan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_revision_plan' => 'Id Revision Plan',
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
    public function getRevisionPlan()
    {
        return $this->hasOne(RevisionPlan::className(), ['id_revision_plan' => 'id_revision_plan']);
    }
}
