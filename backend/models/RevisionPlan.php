<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "revision_plan".
 *
 * @property int $id_revision_plan
 * @property int $id_user
 * @property bool $edition
 * @property int $id_project
 * @property string $start_date
 * @property string $end_date
 * @property bool $finished
 * @property string $type
 * @property string $lettersName
 *
 * @property Project $project
 * @property User $user
 * @property RevisionPlanCriteria[] $revisionPlanCriterias
 * @property ReviewCriteria[] $reviewCriterias
 * @property RevisionPlanLetter[] $revisionPlanLetters
 * @property Letter[] $letters
 * @property RevisionPlanSubmodel[] $revisionPlanSubmodels
 * @property SubModel[] $subModels
 */
class RevisionPlan extends \yii\db\ActiveRecord
{
    public $usuario;
    public $letter;
    public $submodel;
    public $criterias;
    public $letters_name;

    public $late_search;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'revision_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_project'], 'default', 'value' => null],
            [['id_user', 'id_project'], 'integer'],
            [['id_user', 'start_date', 'end_date', 'edition','type', 'finished', 'letter', 'submodel', 'criterias'], 'required'],
            [['edition', 'finished'], 'boolean'],
            [['start_date', 'end_date'], 'safe'],
            [['type'], 'string'],
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
            'id_revision_plan' => 'Id Revision Plan',
            'id_user' => 'Usuario',
            'edition' => 'Edición',
            'id_project' => 'Proyecto',
            'usuario' => 'Usuario',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de fin',
            'finished' => 'Finalizado',
            'late_search' => 'Atrasado',
            'type' => 'Tipo de revisión',
            'letter' => 'Letras',
            'letters_name' => 'Letras',
            'submodel' => 'Componentes lexicográficos',
            'criterias' => 'Criterios de revisión',
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
    public function getRevisionPlanCriterias()
    {
        return $this->hasMany(RevisionPlanCriteria::className(), ['id_revision_plan' => 'id_revision_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewCriterias()
    {
        return $this->hasMany(ReviewCriteria::className(), ['id_review_criteria' => 'id_review_criteria'])->viaTable('revision_plan_criteria', ['id_revision_plan' => 'id_revision_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlanLetters()
    {
        return $this->hasMany(RevisionPlanLetter::className(), ['id_revision_plan' => 'id_revision_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetters()
    {
        return $this->hasMany(Letter::className(), ['id_letter' => 'id_letter'])->viaTable('revision_plan_letter', ['id_revision_plan' => 'id_revision_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlanSubmodels()
    {
        return $this->hasMany(RevisionPlanSubmodel::className(), ['id_revision_plan' => 'id_revision_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModels()
    {
        $submodels = SubModel::find()
            ->innerJoin('redaction_plan_submodel', 'redaction_plan_submodel.id_sub_model = sub_model.id_sub_model')
            ->orderBy('order')->all();
        return $submodels;
    }

    public function getLate()
    {
        if ($this->finished == false && $this->end_date < date('Y-m-d'))
            return "Sí";
        else
            return "No";
    }

    public function deleteAllPlanLetter()
    {
        foreach ($this->revisionPlanLetters as $letter) {
            $letter->delete();
        }
    }

    public function deleteAllPlanSubmodel()
    {
        foreach ($this->revisionPlanSubmodels as $submodel) {
            $submodel->delete();
        }
    }

    public function deleteAllPlanCriteria()
    {
        foreach ($this->revisionPlanCriterias as $criteria) {
            $criteria->delete();
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
        for ($i = 0 ; $i < count($this->subModels); $i++){
            if($i == 0)
                $submodelsName = "[".$this->subModels[$i]->getElementsName()."]";
            else{
                $submodelsName = $submodelsName.', ['.$this->subModels[$i]->getElementsName()."]";
            }
        }
        return $submodelsName;
    }

    public function getCriteriasName()
    {
        $lettersName = "";
        for ($i = 0 ; $i < count($this->reviewCriterias); $i++){
            if($i == 0)
                $lettersName = $this->reviewCriterias[$i]->criteria;
            else{
                $lettersName = $lettersName.', '.$this->reviewCriterias[$i]->criteria;
            }
        }
        return $lettersName;
    }
}
