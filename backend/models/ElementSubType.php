<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "element_sub_type".
 *
 * @property int $id_element_sub_type
 * @property int $id_element_type
 * @property string $name
 * @property bool $removed
 *
 * @property ElementType $elementType
 * @property SubElement[] $subElements
 */
class ElementSubType extends \yii\db\ActiveRecord
{
    public $element_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'element_sub_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_element_type'], 'default', 'value' => null],
            [['id_element_type'], 'integer'],
            [['name'], 'string'],
            [['id_element_type', 'name'], 'required'],
            [['removed'], 'boolean'],
            [['id_element_type'], 'exist', 'skipOnError' => true, 'targetClass' => ElementType::className(), 'targetAttribute' => ['id_element_type' => 'id_element_type']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_element_sub_type' => 'Id Element Sub Type',
            'id_element_type' => 'Elemento lexicográfico',
            'element_name' => 'Elemento lexicográfico',
            'name' => 'Subelemento lexicográfico',
            'removed' => 'removed',
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
    public function getSubElements()
    {
        return $this->hasMany(SubElement::className(), ['id_element_sub_type' => 'id_element_sub_type']);
    }

    public function getTypeName()
    {
        if ($this->id_element_type != '')
            return $this->elementType->name;
        else
            return "";
    }
}
