<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "element_separator".
 *
 * @property int $id_sub_model
 * @property int $id_element
 * @property int $id_separator
 * @property int $order
 *
 * @property Element $element
 * @property Separator $separator
 * @property SubModel $subModel
 */
class ElementSeparator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'element_separator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_sub_model', 'id_element', 'id_separator', 'order'], 'required'],
            [['id_sub_model', 'id_element', 'id_separator', 'order'], 'default', 'value' => null],
            [['id_sub_model', 'id_element', 'id_separator', 'order'], 'integer'],
            [['id_sub_model', 'id_element', 'id_separator'], 'unique', 'targetAttribute' => ['id_sub_model', 'id_element', 'id_separator']],
            [['id_element'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(), 'targetAttribute' => ['id_element' => 'id_element']],
            [['id_separator'], 'exist', 'skipOnError' => true, 'targetClass' => Separator::className(), 'targetAttribute' => ['id_separator' => 'id_separator']],
            [['id_sub_model'], 'exist', 'skipOnError' => true, 'targetClass' => SubModel::className(), 'targetAttribute' => ['id_sub_model' => 'id_sub_model']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_sub_model' => 'Id Sub Model',
            'id_element' => 'Id Element',
            'id_separator' => 'Id Separator',
            'order' => 'Order',
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
    public function getSeparator()
    {
        return $this->hasOne(Separator::className(), ['id_separator' => 'id_separator']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModel()
    {
        return $this->hasOne(SubModel::className(), ['id_sub_model' => 'id_sub_model']);
    }
}
