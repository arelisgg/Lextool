<?php

namespace backend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "redaction_plan".
 *
 * @property int $id_redaction_plan
 * @property int $id_project
 * @property int $id_user
 * @property string $start_date
 * @property string $end_date
 * @property bool $finished
 * @property bool $late
 * @property string $redaction_plan_name
 * @property string $lettersName
 *
 * @property Project $project
 * @property User $user
 * @property RedactionPlanSubmodel[] $redactionPlanSubmodels
 * @property SubModel[] $submodels
 * @property RedactionPlanLetter[] $redactionPlanLetters
 * @property Letter[] $letters
 */
class RedactionPlan extends \yii\db\ActiveRecord
{
    public $usuario;
    public $late_search;
    public $letter;
    public $submodel;
    public $letters_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'redaction_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_user'], 'default', 'value' => null],
            [['id_project', 'id_user'], 'integer'],
            [['id_user', 'start_date', 'end_date', 'finished', 'letter', 'submodel'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['finished'], 'boolean'],
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
            'id_redaction_plan' => 'Id Redaction Plan',
            'id_project' => 'Id Project',
            'id_user' => 'Usuario',
            'usuario' => 'Usuario',
            'finished' => 'Finalizado',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de fin',
            'late' => 'Atrasado',
            'late_search' => 'Atrasado',
            'letter' => 'Letras',
            'letters_name' => 'Letras',
            'submodel' => 'Componentes lexicográficos',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedactionPlanSubmodels()
    {
        return $this->hasMany(RedactionPlanSubmodel::className(), ['id_redaction_plan' => 'id_redaction_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmodels()
    {
        $submodels = SubModel::find()
            ->innerJoin('redaction_plan_submodel', 'redaction_plan_submodel.id_sub_model = sub_model.id_sub_model')
            ->andWhere(['=', 'redaction_plan_submodel.id_redaction_plan', $this->id_redaction_plan])->orderBy('order')->all();
        return $submodels;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedactionPlanLetters()
    {
        return $this->hasMany(RedactionPlanLetter::className(), ['id_redaction_plan' => 'id_redaction_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetters()
    {
        return $this->hasMany(Letter::className(), ['id_letter' => 'id_letter'])->viaTable('redaction_plan_letter', ['id_redaction_plan' => 'id_redaction_plan']);
    }

    public function deleteAllPlanLetter()
    {
        foreach ($this->redactionPlanLetters as $letter) {
            $letter->delete();
        }
    }

    public function deleteAllPlanSubmodel()
    {
        foreach ($this->redactionPlanSubmodels as $submodel) {
            $submodel->delete();
        }
    }

    public function getLettersName()
    {
        $lettersName = "";
        for ($i = 0 ; $i < count($this->letters); $i++){
            if($i == 0)
                $lettersName = $this->letters[$i]->letter;
            else{
                $lettersName = $lettersName.', '.$this->letters[$i]->letter;
            }
        }
        return $lettersName;
    }

    public function getSubmodelName()
    {

        $submodelsName = "";

        for ($i = 0 ; $i < count($this->submodels); $i++){
            if($i == 0)
                $submodelsName = "[".$this->submodels[$i]->getElementsName()."]";
            else{
                $submodelsName = $submodelsName.', ['.$this->submodels[$i]->getElementsName()."]";
            }
        }
        return $submodelsName;
    }

    public function getLate()
    {
        if ($this->finished == false && $this->end_date < date('Y-m-d'))
            return "Sí";
        else
            return "No";
    }

    public function getRedaction_plan_name()
    {
        return $this->user->username." - ".$this->getLettersName();
    }

}
