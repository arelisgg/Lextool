<?php

namespace backend\models;

use Yii;
use common\models\User;


/**
 * This is the model class for table "auth_assignment".
 *
 * @property string $item_name
 * @property int $user_id
 * @property int $created_at
 *
 * @property AuthItem $itemName
 * @property User $user
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    public $usuario;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['user_id', 'created_at'], 'default', 'value' => null],
            [['user_id', 'created_at'], 'integer'],
            [['item_name'], 'string', 'max' => 64],
            //[['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id_user']],
            [['user_id', 'item_name'], 'exist_auth_assignment'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Rol',
            'created_at' => 'Created At',
            'user_id' => 'Usuario',
            'usuario' => 'Usuario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id_user' => 'user_id']);
    }

    public static function getRolesName($user_id){
        $rolesName= '';
        $roles = AuthAssignment::findAll(['user_id'=>$user_id]);
        sort($roles);
        for($i = 0; $i < count($roles) ;$i++){
            $separador = '';
            if($i != 0 && $i <= count($roles)-1){
                $separador = ', ';
            }
            $rolesName = $rolesName.$separador.$roles[$i]->item_name;
        }
        return $rolesName;
    }

    public function exist_auth_assignment($attribute, $params)
    {
        $auth_assignment = AuthAssignment::findOne(['item_name' => $this->item_name, 'user_id' => $this->user_id]);
        if($auth_assignment != null){
            $this->addError($attribute, "El usuario ya tiene este rol asignado");
        }
    }

}
