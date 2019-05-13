<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "illustration_rev_plan".
 *
 * @property int $id_illustration_rev_plan
 * @property int $id_user
 * @property int $id_illustration_plan
 * @property bool $edition
 * @property int $id_project
 * @property string $start_date
 * @property string $end_date
 * @property bool $finished
 * @property bool $late
 *
 * @property IllustrationPlan $illustrationPlan
 * @property Project $project
 * @property User $user
 */
class IllustrationRevPlan extends \yii\db\ActiveRecord
{
    public $usuario;
    public $illustration_plan;

    public $late_search;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'illustration_rev_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_illustration_plan', 'id_project'], 'default', 'value' => null],
            [['id_user', 'id_illustration_plan', 'id_project'], 'integer'],
            [['id_user', 'id_illustration_plan', 'id_project', 'start_date', 'end_date', 'finished'], 'required'],
            [['edition', 'finished'], 'boolean'],
            [['start_date', 'end_date'], 'safe'],
            [['id_illustration_plan'], 'exist', 'skipOnError' => true, 'targetClass' => IllustrationPlan::className(), 'targetAttribute' => ['id_illustration_plan' => 'id_illustration_plan']],
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
            'id_illustration_rev_plan' => 'Id Illustration Rev Plan',
            'id_user' => 'Usuario',
            'id_illustration_plan' => 'Plan de ilustración',
            'illustration_plan' => 'Plan de ilustración',
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
    public function getIllustrationPlan()
    {
        return $this->hasOne(IllustrationPlan::className(), ['id_illustration_plan' => 'id_illustration_plan']);
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
