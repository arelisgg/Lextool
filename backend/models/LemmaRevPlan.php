<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "lemma_rev_plan".
 *
 * @property int $id_rev_plan
 * @property int $id_user
 * @property int $id_ext_plan
 * @property bool $edition
 * @property int $id_project
 * @property string $start_date
 * @property string $end_date
 * @property bool $finished
 * @property bool $late
 *
 * @property LemmaExtPlan $extPlan
 * @property Project $project
 * @property User $user
 */
class LemmaRevPlan extends \yii\db\ActiveRecord
{
    public $usuario;
    public $lemma_ext_plan;

    public $late_search;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lemma_rev_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_ext_plan', 'id_project'], 'default', 'value' => null],
            [['id_user', 'id_ext_plan', 'id_project'], 'integer'],
            [['edition', 'finished'], 'boolean'],
            [['id_user', 'edition', 'id_ext_plan', 'start_date', 'end_date', 'finished'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['id_ext_plan'], 'exist', 'skipOnError' => true, 'targetClass' => LemmaExtPlan::className(), 'targetAttribute' => ['id_ext_plan' => 'id_lemma_ext_plan']],
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
            'id_user' => 'Usuario',
            'id_ext_plan' => 'Plan de extracción de lema',
            'lemma_ext_plan' => 'Plan de extracción de lema',
            'edition' => 'Edición',
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
    public function getExtPlan()
    {
        return $this->hasOne(LemmaExtPlan::className(), ['id_lemma_ext_plan' => 'id_ext_plan']);
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
