<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "log".
 *
 * @property int $id_log
 * @property int $id_user
 * @property string $ip
 * @property string $action
 * @property string $table
 * @property string $date
 * @property string $time
 * @property string $record
 *
 * @property User $user
 */
class Log extends \yii\db\ActiveRecord
{
    public $usuario;
    public $rolesName;
    public $rolesNameProject;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user'], 'default', 'value' => null],
            [['id_user'], 'integer'],
            [['ip', 'action', 'table', 'record'], 'string'],
            [['date', 'time'], 'safe'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id_user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_log' => 'Id Log',
            'id_user' => 'Usuario',
            'ip' => 'Dirección IP',
            'action' => 'Acción',
            'table' => 'Tabla',
            'date' => 'Fecha',
            'time' => 'Hora',
            'record' => 'Registro',
            'usuario' => 'Usuario',
            'rolesName' => 'Roles',
            'rolesNameProject' => 'Roles proyecto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id_user' => 'id_user']);
    }
}
