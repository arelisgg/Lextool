<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sub_model_separator".
 *
 * @property int $id_separator
 * @property int $id_sub_model
 * @property int $order
 *
 * @property Separator $separator
 * @property SubModel $subModel
 */
class SubModelSeparator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_model_separator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_separator', 'id_sub_model', 'order'], 'required'],
            [['id_separator', 'id_sub_model', 'order'], 'default', 'value' => null],
            [['id_separator', 'id_sub_model', 'order'], 'integer'],
            [['id_separator', 'id_sub_model'], 'unique', 'targetAttribute' => ['id_separator', 'id_sub_model']],
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
            'id_separator' => 'Id Separator',
            'id_sub_model' => 'Id Sub Model',
            'order' => 'Order',
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
    public function getSubModel()
    {
        return $this->hasOne(SubModel::className(), ['id_sub_model' => 'id_sub_model']);
    }
}
