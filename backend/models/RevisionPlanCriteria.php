<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "revision_plan_criteria".
 *
 * @property int $id_revision_plan
 * @property int $id_review_criteria
 *
 * @property ReviewCriteria $reviewCriteria
 * @property RevisionPlan $revisionPlan
 */
class RevisionPlanCriteria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'revision_plan_criteria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_revision_plan', 'id_review_criteria'], 'required'],
            [['id_revision_plan', 'id_review_criteria'], 'default', 'value' => null],
            [['id_revision_plan', 'id_review_criteria'], 'integer'],
            [['id_revision_plan', 'id_review_criteria'], 'unique', 'targetAttribute' => ['id_revision_plan', 'id_review_criteria']],
            [['id_review_criteria'], 'exist', 'skipOnError' => true, 'targetClass' => ReviewCriteria::className(), 'targetAttribute' => ['id_review_criteria' => 'id_review_criteria']],
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
            'id_review_criteria' => 'Id Review Criteria',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewCriteria()
    {
        return $this->hasOne(ReviewCriteria::className(), ['id_review_criteria' => 'id_review_criteria']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlan()
    {
        return $this->hasOne(RevisionPlan::className(), ['id_revision_plan' => 'id_revision_plan']);
    }
}
