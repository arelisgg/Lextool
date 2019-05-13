<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "separator".
 *
 * @property int $id_separator
 * @property string $type
 * @property string $representation
 * @property string $scope
 * @property int $id_project
 *
 * @property Project $project
 */
class Separator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'separator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'representation', 'scope'], 'string'],
            [['type', 'representation', 'scope'], 'required'],
            [['id_project'], 'default', 'value' => null],
            [['id_project'], 'integer'],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_separator' => 'Id Separator',
            'type' => 'Tipo',
            'representation' => 'Representación',
            'scope' => 'Alcance',
            'id_project' => 'Id Project',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id_project' => 'id_project']);
    }
}
