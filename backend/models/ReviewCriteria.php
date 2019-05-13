<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "review_criteria".
 *
 * @property int $id_review_criteria
 * @property string $criteria
 * @property bool $removed
 *
 * @property RevisionPlanCriteria[] $revisionPlanCriterias
 * @property RevisionPlan[] $revisionPlans
 */
class ReviewCriteria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review_criteria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['criteria'], 'string'],
            [['criteria'], 'required'],
            [['removed'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_review_criteria' => 'Id Review Criteria',
            'criteria' => 'Criterio de revisión',
            'removed' => 'Removed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlanCriterias()
    {
        return $this->hasMany(RevisionPlanCriteria::className(), ['id_review_criteria' => 'id_review_criteria']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlans()
    {
        return $this->hasMany(RevisionPlan::className(), ['id_revision_plan' => 'id_revision_plan'])->viaTable('revision_plan_criteria', ['id_review_criteria' => 'id_review_criteria']);
    }
}
