<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "template_separator".
 *
 * @property int $id_template
 * @property int $id_separator
 * @property int $order
 * @property int $id_sub_model
 *
 * @property Separator $separator
 * @property Templates $template
 * @property SubModel $submodel
 */
class TemplateSeparator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template_separator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_separator', 'id_template','id_sub_model', 'order'], 'required'],
            [['id_separator', 'id_template','id_sub_model', 'order'], 'default', 'value' => null],
            [['id_separator', 'id_template','id_sub_model', 'order'], 'integer'],
            [['id_separator', 'id_sub_model','id_template'], 'unique', 'targetAttribute' => ['id_separator', 'id_template']],
            [['id_separator'], 'exist', 'skipOnError' => true, 'targetClass' => Separator::className(), 'targetAttribute' => ['id_separator' => 'id_separator']],
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
            'id_separator' => 'Id Separator',
            'order' => 'Order',
            'id_template'=> 'Id Template'
        ];
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
    public function getTemplate()
    {
        return $this->hasOne(Templates::className(), ['id_template' => 'id_template']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModels()
    {
        return $this->hasOne(SubModel::className(), ['id_sub_model' => 'id_sub_model']);
    }
}
