<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "doc_type".
 *
 * @property int $id_doc_type
 * @property string $name
 * @property bool $removed
 *
 * @property ComplementaryDoc[] $complementaryDocs
 * @property DocExtPlan[] $docExtPlans
 * @property Document[] $documents
 */
class DocType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['name'], 'required'],
            [['removed'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_doc_type' => 'Id Doc Type',
            'name' => 'Nombre',
            'removed' => 'removed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComplementaryDocs()
    {
        return $this->hasMany(ComplementaryDoc::className(), ['id_doc_type' => 'id_doc_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocExtPlans()
    {
        return $this->hasMany(DocExtPlan::className(), ['id_doc_type' => 'id_doc_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['id_doc_type' => 'id_doc_type']);
    }
}
