<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "element_type".
 *
 * @property int $id_element_type
 * @property string $name
 * @property bool $removed
 *
 * @property Element[] $elements
 * @property ElementSubType[] $elementSubTypes
 */
class ElementType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'element_type';
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
            'id_element_type' => 'Id Element Type',
            'name' => 'Nombre',
            'removed' => 'removed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElements()
    {
        return $this->hasMany(Element::className(), ['id_element_type' => 'id_element_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementSubTypes()
    {
        return $this->hasMany(ElementSubType::className(), ['id_element_type' => 'id_element_type']);
    }
}
