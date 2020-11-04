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
            [['id_project', 'order'], 'default', 'value' => null],
            [['id_project', 'order'], 'integer'],
            [['name'],'string'],
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
            'id_template_type' => 'Id template type',
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
    public function getSubModelSeparators()
    {
        return $this->hasMany(SubModelSeparator::className(), ['id_sub_model' => 'id_sub_model']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeparators()
    {
        return $this->hasMany(Separator::className(), ['id_separator' => 'id_separator'])->viaTable('sub_model_separator', ['id_sub_model' => 'id_sub_model']);
    }


}
