<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "doc_make_document".
 *
 * @property int $id_doc_make_plan
 * @property int $id_doc_type
 *
 * @property DocMakePlan $docMakePlan
 * @property DocType $docType
 */
class DocMakeDocument extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_make_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_doc_make_plan', 'id_doc_type'], 'required'],
            [['id_doc_make_plan', 'id_doc_type'], 'default', 'value' => null],
            [['id_doc_make_plan', 'id_doc_type'], 'integer'],
            [['id_doc_make_plan', 'id_doc_type'], 'unique', 'targetAttribute' => ['id_doc_make_plan', 'id_doc_type']],
            [['id_doc_make_plan'], 'exist', 'skipOnError' => true, 'targetClass' => DocMakePlan::className(), 'targetAttribute' => ['id_doc_make_plan' => 'id_doc_make_plan']],
            [['id_doc_type'], 'exist', 'skipOnError' => true, 'targetClass' => DocType::className(), 'targetAttribute' => ['id_doc_type' => 'id_doc_type']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_doc_make_plan' => 'Id Doc Make Plan',
            'id_doc_type' => 'Id Doc Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocMakePlan()
    {
        return $this->hasOne(DocMakePlan::className(), ['id_doc_make_plan' => 'id_doc_make_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocType()
    {
        return $this->hasOne(DocType::className(), ['id_doc_type' => 'id_doc_type']);
    }
}
