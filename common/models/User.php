<?php
namespace common\models;

use backend\models\AuthAssignment;
use backend\models\AuthItem;
use backend\models\DocExtPlan;
use backend\models\DocMakePlan;
use backend\models\DocRevPlan;
use backend\models\IllustrationPlan;
use backend\models\IllustrationRevPlan;
use backend\models\Lemma;
use backend\models\LemmaExtPlan;
use backend\models\LemmaRevPlan;
use backend\models\RedactionPlan;
use backend\models\RevisionPlan;
use backend\models\UserProject;
use backend\models\Project;
use backend\models\Log;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id_user
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $password write-only password
 * @property string $full_name
 * @property string $identification
 * @property bool $enabled
 * @property string $rolesName
 * @property string $rolesNameProject
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property DocExtPlan[] $docExtPlans
 * @property DocMakePlan[] $docMakePlans
 * @property DocRevPlan[] $docRevPlans
 * @property IllustrationPlan[] $illustrationPlans
 * @property IllustrationRevPlan[] $illustrationRevPlans
 * @property Lemma[] $lemmas
 * @property LemmaExtPlan[] $lemmaExtPlans
 * @property LemmaRevPlan[] $lemmaRevPlans
 * @property Log[] $logs
 * @property RedactionPlan[] $redactionPlans
 * @property RevisionPlan[] $revisionPlans
 * @property UserProject[] $userProjects
 * @property Project[] $projects
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $rol;
    public $oldPassword;
    public $password;
    public $confirmPassword;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'full_name', 'password', 'confirmPassword', 'rol'], 'required'],
            [['oldPassword'], 'required', 'on' => 'update'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['full_name'], 'string'],
            [['identification'], 'integer'],
            ['identification', 'match', 'pattern'=>"/^.{11,11}$/", 'message'=>'El carnet de identidad contiene 11 dígitos'],
            [['password', 'confirmPassword', 'oldPassword'], 'match', 'pattern'=>"/^.{8,}$/", 'message'=>'Este campo debería contener al menos 8 caracteres'],
            [['enabled'], 'boolean'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password'], 'checkPassword'],
            [['confirmPassword'], 'checkPassword'],
            [['oldPassword'], 'checkOldPassword'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'ID',
            'username' => 'Usuario',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'oldPassword' => 'Contraseña',
            'password' => 'Contraseña',
            'confirmPassword' => 'Confirmar contraseña',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'full_name' => 'Nombre y apellidos',
            'identification' => 'Identificación',
            'enabled' => 'Activo',
            'rol' => 'Roles',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocExtPlans()
    {
        return $this->hasMany(DocExtPlan::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocMakePlans()
    {
        return $this->hasMany(DocMakePlan::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocRevPlans()
    {
        return $this->hasMany(DocRevPlan::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationPlans()
    {
        return $this->hasMany(IllustrationPlan::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationRevPlans()
    {
        return $this->hasMany(IllustrationRevPlan::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmas()
    {
        return $this->hasMany(Lemma::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaExtPlans()
    {
        return $this->hasMany(LemmaExtPlan::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaRevPlans()
    {
        return $this->hasMany(LemmaRevPlan::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedactionPlans()
    {
        return $this->hasMany(RedactionPlan::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlans()
    {
        return $this->hasMany(RevisionPlan::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProjects()
    {
        return $this->hasMany(UserProject::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['id_project' => 'id_project'])->viaTable('user_project', ['id_user' => 'id_user']);
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id_user' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = static::findOne(['username' => $username]);
        if ($user == null){
            $user = static::findOne(['email' => $username]);
        }
        return $user;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            //'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
        //return $this->password_hash === sha1($password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        //$this->password_hash === sha1($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function checkPassword($attribute, $params)
    {
        if($this->password != $this->confirmPassword && $this->password != null &&  $this->confirmPassword != null) {
            $this->addError($attribute, "Confirme la contraseña");
        }

    }

    public function checkOldPassword($attribute, $params)
    {
        if(!$this->validatePassword($this->oldPassword))
        {
            $this->addError($attribute, "Contraseña Incorrecta");
        }
    }

    public function deleteAllRoles()
    {
        foreach ($this->authAssignments as $item) {
            $item->delete();
        }
    }

    public function getRolesName(){

        $rolesName = "";

        for ($i = 0 ; $i < count($this->authAssignments); $i++){
            if($i == 0)
                $rolesName = $this->authAssignments[$i]->item_name;
            else{
                $rolesName = $rolesName.', '.$this->authAssignments[$i]->item_name;
            }
        }
        return $rolesName;
    }

    public function getRolesNameProject(){

        $rolesName = "";
        $roles = [];
        for ($i = 0 ; $i < count($this->userProjects); $i++){
            array_push($roles, $this->userProjects[$i]->role);
        }
        $roles = array_unique($roles);
        for ($i = 0 ; $i < count($roles); $i++){
            if($i == 0)
                $rolesName = $roles[$i];
            else{
                $rolesName = $rolesName.', '.$roles[$i];
            }
        }

        return $rolesName;
    }


    public static function userCanProjectAndRol($id_project, $role){
        $assings = UserProject::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'role' => $role]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanExtractionLemma($id_project){
        $assings = LemmaExtPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanRevitionLemma($id_project){
        $assings = LemmaRevPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanExtractionDocument($id_project){
        $assings = DocExtPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanRevitionDocument($id_project){
        $assings = DocRevPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanConfectionDocument($id_project){
        $assings = DocMakePlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanRedaction($id_project){
        $assings = RedactionPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanRevition($id_project){
        $assings = RevisionPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanIllustration($id_project){
        $assings = IllustrationPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanIllustrationRev($id_project){
        $assings = IllustrationRevPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanIllustrationLemma($id_project){
        $assings = IllustrationPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'type' => "Lema", 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

    public static function userCanIllustrationDoc($id_project){
        $assings = IllustrationPlan::findAll(['id_user' => Yii::$app->getUser()->identity->id_user, 'id_project' => $id_project, 'type' => "Documento Complementario", 'finished' => false]);
        return count($assings)>0 ? true : false;
    }

}
