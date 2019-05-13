<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "user_project".
 *
 * @property int $id_user_project
 * @property int $id_project
 * @property int $id_user
 * @property string $role
 *
 * @property Project $project
 * @property User $user
 */
class UserProject extends \yii\db\ActiveRecord
{
    public $usuario;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_user', 'role'], 'required'],
            [['id_project', 'id_user'], 'default', 'value' => null],
            [['id_user_project', 'id_project', 'id_user'], 'integer'],
            [['role'], 'string'],
            [['id_project', 'id_user', 'role'], 'unique', 'targetAttribute' => ['id_project', 'id_user', 'role']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id_user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_project' => 'Proyecto',
            'id_user' => 'Usuario',
            'role' => 'Rol',
            'usuario' => 'Usuario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id_user' => 'id_user']);
    }
}
