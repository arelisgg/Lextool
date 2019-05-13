<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "source".
 *
 * @property int $id_source
 * @property int $id_project
 * @property string $name
 * @property string $url
 * @property bool $editable
 *
 * @property DocExtPlan[] $docExtPlans
 * @property Lemma[] $lemmas
 * @property LemmaExtPlanSource[] $lemmaExtPlanSources
 * @property LemmaExtPlan[] $extPlans
 * @property Project $project
 * @property SourceLetter[] $sourceLetters
 * @property Letter[] $letters
 */
class Source extends \yii\db\ActiveRecord
{
    public $letter;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project'], 'default', 'value' => null],
            [['id_project'], 'integer'],
            [['letter'], 'safe'],
            [['name', 'url'], 'string'],
            [['name', 'editable',], 'required'],
            [['editable'], 'integer'],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
            [['url'], 'file', 'on' => 'create',
                'extensions' => 'jpg, jpeg, png, pdf',
                'skipOnEmpty' => false,
                'uploadRequired' => 'No has seleccionado ningún archivo', //Error
                'maxSize' => 1024 * 1024 * 25,
                'tooBig' => 'El tamaño máximo permitido es 25 MB', //Error
                'minSize' => 10, //10 Bytes
                'tooSmall' => 'El tamaño mínimo permitido son 10 Bytes', //Error
                'wrongExtension' => 'El archivo {file} no tiene una de las extensiones permitidas ( {extensions} )', //Error
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_source' => 'Id Source',
            'id_project' => 'Id Project',
            'name' => 'Nombre',
            'url' => 'Archivo',
            'editable' => 'Fuente editable',
            'letter' => 'Letras',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocExtPlans()
    {
        return $this->hasMany(DocExtPlan::className(), ['id_source' => 'id_source']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmas()
    {
        return $this->hasMany(Lemma::className(), ['id_source' => 'id_source']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaExtPlanSources()
    {
        return $this->hasMany(LemmaExtPlanSource::className(), ['id_source' => 'id_source']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtPlans()
    {
        return $this->hasMany(LemmaExtPlan::className(), ['id_lemma_ext_plan' => 'id_ext_plan'])->viaTable('lemma_ext_plan_source', ['id_source' => 'id_source']);
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
    public function getSourceLetters()
    {
        return $this->hasMany(SourceLetter::className(), ['id_source' => 'id_source']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetters()
    {
        return $this->hasMany(Letter::className(), ['id_letter' => 'id_letter'])->viaTable('source_letter', ['id_source' => 'id_source']);
    }

    public function getSourceUrl()
    {
        $url = $this->url != "null" ?
            Yii::$app->urlManager->baseUrl . '/uploads/project/source/' . $this->url :
            'No existe el fichero';
        return $url;
    }

    public function isLetter($letter){

       $letters = $this->getLetters();

       foreach ($letters as $l) {
           if ($l == $letter){
               return true;
           }
       }

       return false;
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

    public function deleteAllSourceLetter()
    {
        foreach ($this->sourceLetters as $letter) {
            $letter->delete();
        }
    }
}
