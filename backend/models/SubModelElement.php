<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sub_model_element".
 *
 * @property int $id_sub_model
 * @property int $id_element
 * @property int $order
 *
 * @property Element $element
 * @property SubModel $subModel
 */
class SubModelElement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_model_element';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_sub_model', 'id_element', 'order'], 'required'],
            [['id_sub_model', 'id_element', 'order'], 'default', 'value' => null],
            [['id_sub_model', 'id_element', 'order'], 'integer'],
            [['id_sub_model', 'id_element'], 'unique', 'targetAttribute' => ['id_sub_model', 'id_element']],
            [['id_element'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(), 'targetAttribute' => ['id_element' => 'id_element']],
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
    public function getSubModel()
    {
        return $this->hasOne(SubModel::className(), ['id_sub_model' => 'id_sub_model']);
    }
}
