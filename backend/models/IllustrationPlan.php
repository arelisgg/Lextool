<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "illustration_plan".
 *
 * @property int $id_illustration_plan
 * @property int $id_project
 * @property int $id_user
 * @property string $start_date
 * @property string $end_date
 * @property bool $finished
 * @property string $type
 * @property bool $late
 * @property string $illustration_plan_name
 * @property string $lettersName
 * @property string $documentsName
 *
 * @property IllustrationDocument[] $illustrationDocuments
 * @property IllustrationLemma[] $illustrationLemmas
 * @property Project $project
 * @property User $user
 * @property IllustrationPlanDocument[] $illustrationPlanDocuments
 * @property ComplementaryDoc[] $documents
 * @property IllustrationPlanLetter[] $illustrationPlanLetters
 * @property Letter[] $letters
 * @property IllustrationRevPlan[] $illustrationRevPlans
 */
class IllustrationPlan extends \yii\db\ActiveRecord
{
    public $usuario;
    public $late_search;
    public $letter;
    public $document;
    public $letters_name;
    public $documents_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'illustration_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_user'], 'default', 'value' => null],
            [['id_project', 'id_user'], 'integer'],
            [['id_user', 'start_date', 'end_date', 'finished', 'type', 'letter', 'document'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['finished'], 'boolean'],
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
            'id_illustration_plan' => 'Id Illustration Plan',
            'id_project' => 'Proyecto',
            'id_user' => 'Usuario',
            'usuario' => 'Usuario',
            'finished' => 'Finalizado',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de fin',
            'late' => 'Atrasado',
            'late_search' => 'Atrasado',
            'letter' => 'Letras',
            'letters_name' => 'Letras',
            'document' => 'Documentos complementarios',
            'documents_name' => 'Documentos complementarios',
            'type' => 'Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationDocuments()
    {
        return $this->hasMany(IllustrationDocument::className(), ['id_illustration_plan' => 'id_illustration_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationLemmas()
    {
        return $this->hasMany(IllustrationLemma::className(), ['id_illustration_plan' => 'id_illustration_plan']);
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
    public function getIllustrationPlanDocuments()
    {
        return $this->hasMany(IllustrationPlanDocument::className(), ['id_illustration_plan' => 'id_illustration_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(ComplementaryDoc::className(), ['id_complementary_doc' => 'id_document'])->viaTable('illustration_plan_document', ['id_illustration_plan' => 'id_illustration_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationPlanLetters()
    {
        return $this->hasMany(IllustrationPlanLetter::className(), ['id_illustration_plan' => 'id_illustration_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetters()
    {
        return $this->hasMany(Letter::className(), ['id_letter' => 'id_letter'])->viaTable('illustration_plan_letter', ['id_illustration_plan' => 'id_illustration_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationRevPlans()
    {
        return $this->hasMany(IllustrationRevPlan::className(), ['id_illustration_plan' => 'id_illustration_plan']);
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
        foreach ($this->illustrationPlanLetters as $letter) {
            $letter->delete();
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

    public function deleteAllPlanDocument()
    {
        foreach ($this->illustrationPlanDocuments as $document) {
            $document->delete();
        }
    }

    public function getDocumentsName()
    {
        $documentsName = "";
        for ($i = 0 ; $i < count($this->documents); $i++){
            if($i == 0)
                $documentsName = $this->documents[$i]->docType->name;
            else{
                $documentsName = $documentsName.', '.$this->documents[$i]->docType->name;
            }
        }
        return $documentsName;
    }

    public function getIllustration_plan_name()
    {
        $result = "";
        if($this->type =="Lema")
            $result = $this->user->full_name." - ".$this->getLettersName();
        else
            $result =  $this->user->full_name." - ".$this->getDocumentsName();
        return $result;
    }
}
