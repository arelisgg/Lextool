<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "revision_plan_submodel".
 *
 * @property int $id_revision_plan
 * @property int $id_sub_model
 *
 * @property RevisionPlan $revisionPlan
 * @property SubModel $subModel
 */
class RevisionPlanSubmodel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'revision_plan_submodel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_revision_plan', 'id_sub_model'], 'required'],
            [['id_revision_plan', 'id_sub_model'], 'default', 'value' => null],
            [['id_revision_plan', 'id_sub_model'], 'integer'],
            [['id_revision_plan', 'id_sub_model'], 'unique', 'targetAttribute' => ['id_revision_plan', 'id_sub_model']],
            [['id_revision_plan'], 'exist', 'skipOnError' => true, 'targetClass' => RevisionPlan::className(), 'targetAttribute' => ['id_revision_plan' => 'id_revision_plan']],
            [['id_sub_model'], 'exist', 'skipOnError' => true, 'targetClass' => SubModel::className(), 'targetAttribute' => ['id_sub_model' => 'id_sub_model']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_revision_plan' => 'Id Revision Plan',
            'id_sub_model' => 'Id Sub Model',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlan()
    {
        return $this->hasOne(RevisionPlan::className(), ['id_revision_plan' => 'id_revision_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModel()
    {
        return $this->hasOne(SubModel::className(), ['id_sub_model' => 'id_sub_model']);
    }
}
