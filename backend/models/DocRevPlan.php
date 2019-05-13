<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "doc_rev_plan".
 *
 * @property int $id_rev_plan
 * @property int $id_ext_doc_plan
 * @property int $id_user
 * @property int $id_project
 * @property string $start_date
 * @property string $end_date
 * @property bool $finished
 * @property bool $late
 *
 * @property DocExtPlan $extDocPlan
 * @property Project $project
 * @property User $user
 */
class DocRevPlan extends \yii\db\ActiveRecord
{
    public $usuario;
    public $doc_ext_plan;

    public $late_search;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_rev_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_ext_doc_plan', 'id_user', 'id_project'], 'default', 'value' => null],
            [['id_ext_doc_plan', 'id_user', 'id_project'], 'integer'],
            [['finished'], 'boolean'],
            [['id_ext_doc_plan', 'id_user', 'start_date', 'end_date', 'finished'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['id_ext_doc_plan'], 'exist', 'skipOnError' => true, 'targetClass' => DocExtPlan::className(), 'targetAttribute' => ['id_ext_doc_plan' => 'id_doc_ext_plan']],
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
            'id_rev_plan' => 'Id Rev Plan',
            'id_ext_doc_plan' => 'Plan de extracción de documentos',
            'doc_ext_plan' => 'Plan de extracción de documentos',
            'id_user' => 'Usuario',
            'id_project' => 'Proyecto',
            'usuario' => 'Usuario',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de fin',
            'finished' => 'Finalizado',
            'late_search' => 'Atrasado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtDocPlan()
    {
        return $this->hasOne(DocExtPlan::className(), ['id_doc_ext_plan' => 'id_ext_doc_plan']);
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

    public function getLate()
    {
        if ($this->finished == false && $this->end_date < date('Y-m-d'))
            return "Sí";
        else
            return "No";
    }
}
