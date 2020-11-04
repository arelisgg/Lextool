<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "template_type".
 *
 * @property int $id_template_type
 * @property string $type
 * @property bool $removed
 *
 * @property Project[] $projects
 */
class TemplateType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
            [['type'], 'required'],
            [['removed'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_template_type' => 'Id Template Type',
            'type' => 'Plantilla',
            'removed' => 'removed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates()
    {
        return $this->hasMany(Templates::className(), ['id_template_type' => 'id_template_type']);
    }
}
