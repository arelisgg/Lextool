<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property int $id_document
 * @property int $id_project
 * @property int $id_doc_type
 * @property string $original_text
 * @property bool $reviewed
 * @property int $id_doc_ext_plan
 *
 * @property DocImage[] $docImages
 * @property DocExtPlan $docExtPlan
 * @property DocType $docType
 * @property Project $project
 */
class Document extends \yii\db\ActiveRecord
{
    public $doc_type;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_doc_type', 'id_doc_ext_plan'], 'default', 'value' => null],
            [['id_project', 'id_doc_type', 'id_doc_ext_plan'], 'integer'],
            [['original_text'], 'string'],
            [['reviewed'], 'boolean'],
            [['id_doc_ext_plan'], 'exist', 'skipOnError' => true, 'targetClass' => DocExtPlan::className(), 'targetAttribute' => ['id_doc_ext_plan' => 'id_doc_ext_plan']],
            [['id_doc_type'], 'exist', 'skipOnError' => true, 'targetClass' => DocType::className(), 'targetAttribute' => ['id_doc_type' => 'id_doc_type']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_document' => 'Id Document',
            'id_project' => 'Proyecto',
            'id_doc_type' => 'Documento complementario',
            'doc_type' => 'Documento complementario',
            'original_text' => 'Texto original',
            'reviewed' => 'Aprobado',
            'id_doc_ext_plan' => 'Id Doc Ext Plan',
            'finished' => 'Finalizado'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocImages()
    {
        return $this->hasMany(DocImage::className(), ['id_document' => 'id_document']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocExtPlan()
    {
        return $this->hasOne(DocExtPlan::className(), ['id_doc_ext_plan' => 'id_doc_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocType()
    {
        return $this->hasOne(DocType::className(), ['id_doc_type' => 'id_doc_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id_project' => 'id_project']);
    }

    public function getDocTypeName()
    {
        if ($this->id_doc_type != null)
            return $this->docType->name;
    }
}
