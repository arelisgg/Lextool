<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sub_model".
 *
 * @property int $id_sub_model
 * @property int $id_project
 * @property string $name
 * @property bool $repeat
 * @property int $order
 * @property bool $required
 * @property string $elementsName
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
class SubModel extends \yii\db\ActiveRecord
{
    public $model_type;
    public $element;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_model';
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
            [['repeat', 'required'], 'boolean'],
            [['name'],'string'],
            //[['id_project', 'name'], 'unique', 'targetAttribute' => ['id_project', 'name']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_sub_model' => 'Id Sub Model',
            'id_project' => 'Id Project',
            'name' => 'Nombre de componente',
            'repeat' => 'Se repite',
            'order' => 'Orden',
            'required' => 'Requerido'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementSeparators()
    {
        return $this->hasMany(ElementSeparator::className(), ['id_sub_model' => 'id_sub_model']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLexArticleElements()
    {
        return $this->hasMany(LexArticleElement::className(), ['id_sub_model' => 'id_sub_model']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedactionPlanSubmodels()
    {
        return $this->hasMany(RedactionPlanSubmodel::className(), ['id_sub_model' => 'id_sub_model']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedactionPlans()
    {
        return $this->hasMany(RedactionPlan::className(), ['id_redaction_plan' => 'id_redaction_plan'])->viaTable('redaction_plan_submodel', ['id_sub_model' => 'id_sub_model']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlanSubmodels()
    {
        return $this->hasMany(RevisionPlanSubmodel::className(), ['id_sub_model' => 'id_sub_model']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlans()
    {
        return $this->hasMany(RevisionPlan::className(), ['id_revision_plan' => 'id_revision_plan'])->viaTable('revision_plan_submodel', ['id_sub_model' => 'id_sub_model']);
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
    public function getSubModelElements()
    {
        return $this->hasMany(SubModelElement::className(), ['id_sub_model' => 'id_sub_model']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElements()
    {
        return $this->hasMany(Element::className(), ['id_element' => 'id_element'])->viaTable('sub_model_element', ['id_sub_model' => 'id_sub_model']);
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

    public function getElementsName()
    {
        $subModelElements = SubModelElement::find()->where(['id_sub_model' => $this->id_sub_model])->orderBy('order')->all();
        $elementsName = "";

        for ($i = 0 ; $i < count($subModelElements); $i++){
            if($i == 0)
                $elementsName = $this->name." - ".$subModelElements[$i]->element->elementType->name;
            else{
                $elementsName = $elementsName.', '.$subModelElements[$i]->element->elementType->name;
            }
        }
        return $elementsName;
    }

    public function getOnlyElementsName()
    {
        $subModelElements = SubModelElement::find()->where(['id_sub_model' => $this->id_sub_model])->orderBy('order')->all();
        $elementsName = "";

        for ($i = 0 ; $i < count($subModelElements); $i++){
            if($i == 0)
                $elementsName = $subModelElements[$i]->element->elementType->name;
            else{
                $elementsName = $elementsName.', '.$subModelElements[$i]->element->elementType->name;
            }
        }
        return $elementsName;
    }
}
