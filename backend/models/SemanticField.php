<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "semantic_field".
 *
 * @property int $id_semantic_field
 * @property string $name
 * @property bool $removed
 */
class SemanticField extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'semantic_field';
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
            'id_semantic_field' => 'Id Semantic Field',
            'name' => 'Nombre',
            'removed' => 'Removed',
        ];
    }
}
