<?php

namespace backend\models;

use backend\models\Templates;
use Yii;

/**
 * This is the model class for table "template_element".
 *
 * @property int $id_template
 * @property int $id_element
 * @property int $order
 *
 * @property Templates $template
 * @property SubModel $subModel
 */
class TemplateElement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template_element';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_element', 'id_template', 'order'], 'required'],
            [['id_element', 'id_template', 'order'], 'default', 'value' => null],
            [['id_element', 'id_template', 'order'], 'integer'],
            [['id_element', 'id_template'], 'unique', 'targetAttribute' => ['id_element', 'id_template']],
            [['id_template'], 'exist', 'skipOnError' => true, 'targetClass' => Templates::className(), 'targetAttribute' => ['id_template' => 'id_template']],
            [['id_element'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(), 'targetAttribute' => ['id_element' => 'id_element']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_element' => 'Id element',
            'id_template' => 'Id template',
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
    public function getTemplate()
    {
        return $this->hasOne(Templates::className(), ['id_template' => 'id_template']);
    }

}
