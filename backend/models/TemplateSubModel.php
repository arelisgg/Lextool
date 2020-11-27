<?php

namespace backend\models;

use backend\models\Templates;
use Yii;

/**
* This is the model class for table "template_sub_model".
*
* @property int $id_template
* @property int $id_sub_model
* @property int $order
*
* @property Templates $template
* @property SubModel $subModel
*/
class TemplateSubModel extends \yii\db\ActiveRecord
{
/**
* {@inheritdoc}
*/
public static function tableName()
{
return 'template_sub_model';
}

/**
* {@inheritdoc}
*/
public function rules()
{
return [
[['id_sub_model', 'id_template', 'order'], 'required'],
[['id_sub_model', 'id_template', 'order'], 'default', 'value' => null],
[['id_sub_model', 'id_template', 'order'], 'integer'],
[['id_sub_model', 'id_template'], 'unique', 'targetAttribute' => ['id_sub_model', 'id_template']],
[['id_template'], 'exist', 'skipOnError' => true, 'targetClass' => Templates::className(), 'targetAttribute' => ['id_template' => 'id_template']],
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
'id_template' => 'Id template',
'order' => 'Order',
];
}


/**
* @return \yii\db\ActiveQuery
*/
public function getSubModel()
{
return $this->hasOne(SubModel::className(), ['id_sub_model' => 'id_sub_model']);
}
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Templates::className(), ['id_template' => 'id_template']);
    }

}
