<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sub_element".
 *
 * @property int $id_sub_element
 * @property int $id_element
 * @property int $id_element_sub_type
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
 * @property Element $element
 * @property ElementSubType $elementSubType
 */
class SubElement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_element';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_element', 'id_element_sub_type', 'font_size'], 'default', 'value' => null],
            [['id_element', 'id_element_sub_type', 'font_size'], 'integer'],
            [['visibility'], 'boolean'],
            [['font', 'font_weight', 'font_style', 'text_decoration', 'color', 'background', 'text_transform'], 'string'],
            [['id_element_sub_type', 'font_size', 'visibility', 'font', 'font_weight', 'font_style', 'text_decoration', 'color', 'background', 'text_transform'], 'required'],
            [['id_element'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(), 'targetAttribute' => ['id_element' => 'id_element']],
            [['id_element_sub_type'], 'exist', 'skipOnError' => true, 'targetClass' => ElementSubType::className(), 'targetAttribute' => ['id_element_sub_type' => 'id_element_sub_type']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_sub_element' => 'Id Sub Element',
            'id_element' => 'Elemento',
            'id_element_sub_type' => 'Tipo de subelemento',
            'visibility' => 'Visibilidad',
            'font' => 'Letra',
            'font_size' => 'Tamaño de letra',
            'font_weight' => 'Font Weight',
            'font_style' => 'Font Style',
            'text_decoration' => 'Text Decoration',
            'color' => 'Color',
            'background' => 'Background',
            'text_transform' => 'Text Transform',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(Element::className(), ['id_element' => 'id_element']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementSubType()
    {
        return $this->hasOne(ElementSubType::className(), ['id_element_sub_type' => 'id_element_sub_type']);
    }

    public function getColorFont(){
        return "#".$this->color;
    }

    public function getColorBack(){
        return "#".$this->background;
    }

    public function getSubTypeName()
    {
        if ($this->id_element_sub_type != '')
            return $this->elementSubType->name;
        else
            return "";
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

    public function isVisible()
    {
        return $this->visibility==1 ? "Si" : "No" ;
    }
}
