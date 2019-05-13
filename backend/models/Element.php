<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "element".
 *
 * @property int $id_element
 * @property int $id_project
 * @property int $id_element_type
 * @property string $property
 * @property bool $visibility
 * @property string $font
 * @property int $font_size
 * @property string $font_weight
 * @property string $font_style
 * @property string $text_decoration
 * @property string $color
 * @property string $background
 * @property string $text_transform
 *
 * @property ElementType $elementType
 * @property Project $project
 * @property SubElement[] $subElements
 * @property SubModelElement[] $subModelElements
 * @property SubModel[] $subModels
 * @property ElementSeparator[] $elementSeparators
 */
class Element extends \yii\db\ActiveRecord
{
    public $element_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'element';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_element_type', 'font_size'], 'default', 'value' => null],
            [['id_project', 'id_element_type', 'font_size'], 'integer'],
            [['id_element_type', 'property', 'visibility', 'font_size', 'font', 'font_weight', 'font_style', 'text_decoration', 'color', 'background', 'text_transform'], 'required'],
            [['property', 'font', 'font_weight', 'font_style', 'text_decoration', 'color', 'background', 'text_transform'], 'string'],
            [['visibility'], 'boolean'],
            [['id_element_type'], 'exist', 'skipOnError' => true, 'targetClass' => ElementType::className(), 'targetAttribute' => ['id_element_type' => 'id_element_type']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_element' => 'Elemento',
            'id_project' => 'Proyecto',
            'id_element_type' => 'Tipo de elemento',
            'element_name' => 'Tipo de elemento',
            'property' => 'Propiedad',
            'visibility' => 'Visibilidad',
            'font' => 'Letra',
            'font_size' => 'Tamaño de letra',
            'font_weight' => 'Negrita',
            'font_style' => 'Italic',
            'text_decoration' => 'Subrayado',
            'color' => 'Color',
            'background' => 'Color de fondo',
            'text_transform' => 'Mayúscula',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementType()
    {
        return $this->hasOne(ElementType::className(), ['id_element_type' => 'id_element_type']);
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
    public function getSubElements()
    {
        return $this->hasMany(SubElement::className(), ['id_element' => 'id_element']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModelElements()
    {
        return $this->hasMany(SubModelElement::className(), ['id_element' => 'id_element']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModels()
    {
        return $this->hasMany(SubModel::className(), ['id_sub_model' => 'id_sub_model'])->viaTable('sub_model_element', ['id_element' => 'id_element']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getElementSeparators()
    {
        return $this->hasMany(ElementSeparator::className(), ['id_element' => 'id_element']);
    }

    public function getTypeName()
    {
        if ($this->id_element_type != '')
            return $this->elementType->name;
        else
            return "";
    }

    public function getColorFont(){
        return "#".$this->color;
    }

    public function getColorBack(){
        return "#".$this->background;
    }

    public function getFontWeightStyle()
    {
        return $this->font_weight=="bold"? "background-color: #f4f4f4; border-color: #ddd;" : "background-color: #ffffff; border-color: #fff;";
    }

    public function getFontStyle()
    {
        return $this->font_style=="italic"? "background-color: #f4f4f4; border-color: #ddd;" : "background-color: #ffffff; border-color: #fff;";
    }

    public function getTextDecorationStyle()
    {
        return $this->text_decoration=="underline"? "background-color: #f4f4f4; border-color: #ddd;" : "background-color: #ffffff; border-color: #fff;";
    }

    public function getTextTransformStyle()
    {
        return $this->text_transform=="uppercase"? "background-color: #f4f4f4; border-color: #ddd;" : "background-color: #ffffff; border-color: #fff;";
    }
}
