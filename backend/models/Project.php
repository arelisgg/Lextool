<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id_project
 * @property string $name
 * @property int $id_dictionary_type
 * @property string $description
 * @property string $status
 * @property string $plant_file
 * @property string $start_date
 * @property string $end_date
 * @property string $image
 *
 * @property ComplementaryDoc[] $complementaryDocs
 * @property DocExtPlan[] $docExtPlans
 * @property DocMakePlan[] $docMakePlans
 * @property DocRevPlan[] $docRevPlans
 * @property Document[] $documents
 * @property Element[] $elements
 * @property Illustration[] $illustrations
 * @property IllustrationPlan[] $illustrationPlans
 * @property IllustrationRevPlan[] $illustrationRevPlans
 * @property Lemma[] $lemmas
 * @property LemmaExtPlan[] $lemmaExtPlans
 * @property LemmaRevPlan[] $lemmaRevPlans
 * @property DictionaryType $dictionaryType
 * @property RedactionPlan[] $redactionPlans
 * @property RevisionPlan[] $revisionPlans
 * @property Separator[] $separators
 * @property Source[] $sources
 * @property SubModel[] $subModels
 * @property UserProject[] $userProjects
 */
class Project extends \yii\db\ActiveRecord
{

    public $dictionary_type;
    public $id_user;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'status', 'plant_file', 'image'], 'string'],
            [['name', 'id_dictionary_type', 'status', 'id_user'], 'required'],
            [['id_dictionary_type'], 'default', 'value' => null],
            [['id_dictionary_type', 'id_user'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['id_dictionary_type'], 'exist', 'skipOnError' => true, 'targetClass' => DictionaryType::className(), 'targetAttribute' => ['id_dictionary_type' => 'id_dictionary_type']],
            [['plant_file'], 'file', 'on' => 'create',
                'extensions' => 'jpg, jpeg, png, doc, docx, pdf, xlsx, xls',
                'skipOnEmpty' => false,
                'uploadRequired' => 'No has seleccionado ningún archivo', //Error
                'maxSize' => 1024 * 1024 * 20, //10 MB
                'tooBig' => 'El tamaño máximo permitido es 20 MB', //Error
                'minSize' => 10, //10 Bytes
                'tooSmall' => 'El tamaño mínimo permitido son 10 Bytes', //Error
                'wrongExtension' => 'El archivo {file} no tiene una de las extensiones permitidas ( {extensions} )', //Error
                'maxFiles' => 1,
                'tooMany' => 'El máximo de archivos permitidos son {limit}', //Error
            ],
            [['image'], 'file', 'extensions' => 'jpg,png,gif,jpeg',
                //'skipOnEmpty' => true,
                //'uploadRequired' => 'No has seleccionado ningún archivo', //Error
                'maxSize' => 1024 * 1024 * 5, //5 MB
                'tooBig' => 'El tamaño máximo permitido es 5 MB', //Error
                'minSize' => 10, //10 Bytes
                'tooSmall' => 'El tamaño mínimo permitido son 10 Bytes', //Error
                'wrongExtension' => 'El archivo {file} no tiene una de las extensiones permitidas ( {extensions} )', //Error
                'maxFiles' => 1,
                'tooMany' => 'El máximo de archivos permitidos son {limit}', //Error
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_project' => 'Id Project',
            'name' => 'Nombre',
            'id_dictionary_type' => 'Tipo de diccionario',
            'dictionary_type' => 'Tipo de diccionario',
            'description' => 'Descripción',
            'status' => 'Estado',
            'plant_file' => 'Planta lexicográfica',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de fin',
            'id_user' => 'Jefe de proyecto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComplementaryDocs()
    {
        return $this->hasMany(ComplementaryDoc::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocExtPlans()
    {
        return $this->hasMany(DocExtPlan::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocMakePlans()
    {
        return $this->hasMany(DocMakePlan::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocRevPlans()
    {
        return $this->hasMany(DocRevPlan::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElements()
    {
        return $this->hasMany(Element::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrations()
    {
        return $this->hasMany(Illustration::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationPlans()
    {
        return $this->hasMany(IllustrationPlan::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationRevPlans()
    {
        return $this->hasMany(IllustrationRevPlan::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmas()
    {
        return $this->hasMany(Lemma::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaExtPlans()
    {
        return $this->hasMany(LemmaExtPlan::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaRevPlans()
    {
        return $this->hasMany(LemmaRevPlan::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDictionaryType()
    {
        return $this->hasOne(DictionaryType::className(), ['id_dictionary_type' => 'id_dictionary_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedactionPlans()
    {
        return $this->hasMany(RedactionPlan::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisionPlans()
    {
        return $this->hasMany(RevisionPlan::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeparators()
    {
        return $this->hasMany(Separator::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSources()
    {
        return $this->hasMany(Source::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModels()
    {
        return $this->hasMany(SubModel::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProjects()
    {
        return $this->hasMany(UserProject::className(), ['id_project' => 'id_project']);
    }

    public function getPlantUrl()
    {
        $diag = $this->plant_file != "null" ?
            Yii::$app->urlManager->baseUrl . '/uploads/project/plant/' . $this->plant_file :
            'No existe el fichero';
        return $diag;
    }

    public function getTypeName()
    {
        if ($this->id_dictionary_type != '')
            return $this->dictionaryType->type;
        else
            return "";
    }

    public function getImageUrl()
    {
        $avatar = $this->image != "" ? $this->image : 'default.jpg';
        $avatar = Yii::$app->urlManager->baseUrl . '/uploads/project/image/' . $avatar;
        return $avatar;
    }
}
