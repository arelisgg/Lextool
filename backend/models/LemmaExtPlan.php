<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "lemma_ext_plan".
 *
 * @property int $id_lemma_ext_plan
 * @property int $id_project
 * @property int $id_user
 * @property bool $finished
 * @property string $start_date
 * @property string $end_date
 * @property string $ext_plan_name
 * @property bool $late
 * @property string $lettersName
 *
 * @property Lemma[] $lemmas
 * @property Project $project
 * @property User $user
 * @property LemmaExtPlanLetter[] $lemmaExtPlanLetters
 * @property Letter[] $letters
 * @property LemmaExtPlanSemanticField[] $lemmaExtPlanSemanticFields
 * @property SemanticField[] $semanticFields
 * @property LemmaExtPlanTemplate[] $lemmaExtPlanTemplates
 * @property Templates[] $template
 * @property LemmaExtPlanSource[] $lemmaExtPlanSources
 * @property Source[] $sources
 * @property LemmaRevPlan[] $lemmaRevPlans
 */
class LemmaExtPlan extends \yii\db\ActiveRecord
{
    public $usuario;
    public $semantic_field;
    public $letter;
    public $source;
    public $late_search;
    public $letters_name;
    public $template;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lemma_ext_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_user'], 'default', 'value' => null],
            [['id_project', 'id_user'], 'integer'],
            [['id_user', 'start_date', 'end_date', 'letter', 'source', 'template', 'semantic_field', 'finished'], 'required'],
            [['finished'], 'boolean'],
            [['start_date', 'end_date'], 'safe'],
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
            'id_lemma_ext_plan' => 'Id Lemma Ext Plan',
            'id_project' => 'Id Project',
            'id_user' => 'Usuario',
            'semantic_field' => 'Campos semánticos',
            'template' => 'Plantillas',
            'finished' => 'Finalizado',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de fin',
            'usuario' => 'Usuario',
            'letter' => 'Letras',
            'letters_name' => 'Letras',
            'source' => 'Fuentes',
            'late' => 'Atrasado',
            'late_search' => 'Atrasado',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmas()
    {
        return $this->hasMany(Lemma::className(), ['id_lemma_ext_plan' => 'id_lemma_ext_plan']);
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
    public function getLemmaExtPlanLetters()
    {
        return $this->hasMany(LemmaExtPlanLetter::className(), ['id_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetters()
    {
        return $this->hasMany(Letter::className(), ['id_letter' => 'id_letter'])->viaTable('lemma_ext_plan_letter', ['id_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaExtPlanSemanticFields()
    {
        return $this->hasMany(LemmaExtPlanSemanticField::className(), ['id_lemma_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemanticFields()
    {
        return $this->hasMany(SemanticField::className(), ['id_semantic_field' => 'id_semantic_field'])->viaTable('lemma_ext_plan_semantic_field', ['id_lemma_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
       public function getLemmaExtPlanTemplates()
    {
        return $this->hasMany(LemmaExtPlanTemplate::className(), ['id_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates()
    {
        return $this->hasMany(Templates::className(), ['id_template' => 'id_template'])->viaTable('lemma_ext_plan_template', ['id_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaExtPlanSources()
    {
        return $this->hasMany(LemmaExtPlanSource::className(), ['id_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSources()
    {
        return $this->hasMany(Source::className(), ['id_source' => 'id_source'])->viaTable('lemma_ext_plan_source', ['id_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaRevPlans()
    {
        return $this->hasMany(LemmaRevPlan::className(), ['id_ext_plan' => 'id_lemma_ext_plan']);
    }

    public function deleteAllPlanSemantic()
    {
        foreach ($this->lemmaExtPlanSemanticFields as $semanticField) {
            $semanticField->delete();
        }
    }
    public function deleteAllPlanTemplates()
    {
        foreach ($this->lemmaExtPlanTemplates as $template) {
            $template->delete();
        }
    }

    public function deleteAllPlanLetter()
    {
        foreach ($this->lemmaExtPlanLetters as $letter) {
            $letter->delete();
        }
    }

    public function deleteAllPlanSource()
    {
        foreach ($this->lemmaExtPlanSources as $source) {
            $source->delete();
        }
    }

    public function getSemanticsName()
    {
        $semanticsName = "";
        for ($i = 0 ; $i < count($this->semanticFields); $i++){
            if($i == 0)
                $semanticsName = $this->semanticFields[$i]->name;
            else{
                $semanticsName = $semanticsName.', '.$this->semanticFields[$i]->name;
            }
        }
        return $semanticsName;
    }
    public function getTemplatesName()
    {
        $templatesName = "";
        for ($i = 0 ; $i < count($this->templates); $i++){
            if($i == 0)
                $templatesName = $this->templates[$i]->name;
            else{
                $templatesName = $templatesName.', '.$this->templates[$i]->name;
            }
        }
        return $templatesName;
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

    public function getSourcesName()
    {
        $sourcesName = "";
        for ($i = 0 ; $i < count($this->sources); $i++){
            if($i == 0)
                $sourcesName = $this->sources[$i]->name;
            else{
                $sourcesName = $sourcesName.', '.$this->sources[$i]->name;
            }
        }
        return $sourcesName;
    }

    public function getExt_plan_name()
    {
        return $this->user->full_name." - ".$this->getLettersName();
    }

    public function getLate()
    {
        if ($this->finished == false && $this->end_date < date('Y-m-d'))
            return "Sí";
        else
            return "No";
    }
}