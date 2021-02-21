<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property int $id_template
 * @property int $id_project
 * @property string $name
 * @property int $id_template_type
 * @property string $ref_file
 *
 *
 *
 * @property ElementSeparator[] $elementSeparators
 * @property LexArticleElement[] $lexArticleElements
 * @property RedactionPlanSubmodel[] $redactionPlanSubmodels
 * @property RedactionPlan[] $redactionPlans
 * @property RevisionPlanSubmodel[] $revisionPlanSubmodels
 * @property RevisionPlan[] $revisionPlans
 * @property Project $project
 * @property SubModelElement[] $subModelElements
 * @property Element[] $elements
 * @property SubModelSeparator[] $subModelSeparators
 * @property Separator[] $separators
 * @property TemplateSeparator[] $templateSeparators
 * @property TemplateSubModel[] $templateSubModels
 * @property TemplateElement[] $templateElements
 */

class Templates extends \yii\db\ActiveRecord
{
    public $model_type;
    public $element;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'name'], 'required'],
            [['id_project'], 'default', 'value' => null],
            [['id_project'], 'integer'],
            [['name'],'string'],
            [['ref_file'],'string'],
            //[['id_project', 'name'], 'unique', 'targetAttribute' => ['id_project', 'name']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
            [['id_template_type'], 'default', 'value' => null],
            [['id_template_type'], 'integer'],
            [['id_template_type'], 'exist', 'skipOnError' => true, 'targetClass' => TemplateType::className(), 'targetAttribute' => ['id_template_type' => 'id_template_type']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_template' => 'Id template',
            'id_project' => 'Id Project',
            'name' => 'Nombre de la plantilla',
            'id_template_type' => 'Tipo de plantilla',
            'ref_file' => 'Archivo de Referencia',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModels()
    {
        return $this->hasMany(SubModel::className(), ['id_sub_model' => 'id_sub_model'])->viaTable('sub_model', ['id_template' => 'id_template']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplateSeparators()
    {
        return $this->hasMany(TemplateSeparator::className(), ['id_template' => 'id_template']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeparators()
    {
        return $this->hasMany(Separator::className(), ['id_separator' => 'id_separator'])->viaTable('template_separator', ['id_template' => 'id_template']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElements()
    {
        return $this->hasMany(Element::className(), ['id_element' => 'id_element'])->viaTable('template_element', ['id_template' => 'id_template']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplateElements()
    {
        return $this->hasMany(TemplateElement::className(), ['id_template' => 'id_template']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplateSubModels()
    {
        return $this->hasMany(TemplateSubModel::className(), ['id_template' => 'id_template']);
    }
}
