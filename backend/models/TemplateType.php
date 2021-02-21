<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "template_type".
 *
 * @property int $id_template_type
 * @property string $name
 * @property bool $removed
 * @property string $stage
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
            [['name'], 'string'],
            [['name'], 'required'],
            [['stage'], 'string'],
            [['stage'], 'required'],
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
            'name' => 'Plantilla',
            'removed' => 'removed',
            'stage' => 'Etapa',
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
