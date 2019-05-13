<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dictionary_type".
 *
 * @property int $id_dictionary_type
 * @property string $type
 * @property bool $removed
 *
 * @property Project[] $projects
 */
class DictionaryType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dictionary_type';
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
            'id_dictionary_type' => 'Id Dictionary Type',
            'type' => 'Diccionario',
            'removed' => 'removed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['id_dictionary_type' => 'id_dictionary_type']);
    }
}
