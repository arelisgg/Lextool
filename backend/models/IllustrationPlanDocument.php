<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "illustration_plan_document".
 *
 * @property int $id_document
 * @property int $id_illustration_plan
 *
 * @property ComplementaryDoc $document
 * @property IllustrationPlan $illustrationPlan
 */
class IllustrationPlanDocument extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'illustration_plan_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_document', 'id_illustration_plan'], 'required'],
            [['id_document', 'id_illustration_plan'], 'default', 'value' => null],
            [['id_document', 'id_illustration_plan'], 'integer'],
            [['id_document', 'id_illustration_plan'], 'unique', 'targetAttribute' => ['id_document', 'id_illustration_plan']],
            [['id_document'], 'exist', 'skipOnError' => true, 'targetClass' => ComplementaryDoc::className(), 'targetAttribute' => ['id_document' => 'id_complementary_doc']],
            [['id_illustration_plan'], 'exist', 'skipOnError' => true, 'targetClass' => IllustrationPlan::className(), 'targetAttribute' => ['id_illustration_plan' => 'id_illustration_plan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_document' => 'Id Document',
            'id_illustration_plan' => 'Id Illustration Plan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(ComplementaryDoc::className(), ['id_complementary_doc' => 'id_document']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationPlan()
    {
        return $this->hasOne(IllustrationPlan::className(), ['id_illustration_plan' => 'id_illustration_plan']);
    }
}
