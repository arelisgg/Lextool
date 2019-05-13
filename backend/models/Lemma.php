<?php

namespace backend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "lemma".
 *
 * @property int $id_lemma
 * @property int $id_project
 * @property int $id_letter
 * @property int $id_source
 * @property int $id_user
 * @property string $extracted_lemma
 * @property string $original_lemma
 * @property string $structure
 * @property string $substructure
 * @property string $original_text
 * @property string $remark
 * @property bool $agree
 * @property bool $finished
 * @property bool $lemario
 * @property bool $homonym
 * @property int $id_lemma_ext_plan
 *
 * @property LemmaExtPlan $lemmaExtPlan
 * @property Letter $letter
 * @property Project $project
 * @property Source $source
 * @property User $user
 * @property LemmaImage[] $lemmaImages
 * @property LexArticle $lexArticle
 * @property IllustrationLemma[] $illustrationLemmas
 * @property Illustration[] $illustrations
 */
class Lemma extends \yii\db\ActiveRecord
{
    public $letter_name;
    public $usuario;
    public $source_name;

    public $lexArtFinished;
    public $lexArtReviewed;
    public $lemma;
    public $extReviewed;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lemma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_letter', 'id_source', 'id_user', 'id_lemma_ext_plan'], 'default', 'value' => null],
            [['id_project', 'id_letter', 'id_source', 'id_user', 'id_lemma_ext_plan'], 'integer'],
            [['extracted_lemma', 'original_lemma', 'structure', 'substructure', 'original_text', 'remark'], 'string'],
            [['agree', 'finished', 'lemario', 'homonym'], 'boolean'],
            [['id_lemma_ext_plan'], 'exist', 'skipOnError' => true, 'targetClass' => LemmaExtPlan::className(), 'targetAttribute' => ['id_lemma_ext_plan' => 'id_lemma_ext_plan']],
            [['id_letter'], 'exist', 'skipOnError' => true, 'targetClass' => Letter::className(), 'targetAttribute' => ['id_letter' => 'id_letter']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
            [['id_source'], 'exist', 'skipOnError' => true, 'targetClass' => Source::className(), 'targetAttribute' => ['id_source' => 'id_source']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id_user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_lemma' => 'Id Lemma',
            'id_project' => 'Id Project',
            'id_letter' => 'Letra',
            'id_source' => 'Id Source',
            'id_user' => 'Id User',
            'extracted_lemma' => 'Lema extraído',
            'original_lemma' => 'Lema original',
            'structure' => 'Estructura',
            'substructure' => 'Elemento lexicográfico',
            'original_text' => 'Texto original',
            'remark' => 'Remark',
            'agree' => 'Aprobado',
            'finished' => 'Lema aprobado',
            'lemario' => 'Lemario',
            'homonym' => 'Homónimo',
            'id_lemma_ext_plan' => 'Id Lemma Ext Plan',
            'letter_name' => 'Letra',
            'usuario' => 'Usuario',
            'source_name' => 'Fuente',
            'lexArtFinished' => 'Redacción finalizada',
            'lexArtReviewed' => 'Redacción aprobada',
            'extReviewed' => 'Extracción aprobada',
            'lemma' => 'Lema',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaExtPlan()
    {
        return $this->hasOne(LemmaExtPlan::className(), ['id_lemma_ext_plan' => 'id_lemma_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetter()
    {
        return $this->hasOne(Letter::className(), ['id_letter' => 'id_letter']);
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
    public function getSource()
    {
        return $this->hasOne(Source::className(), ['id_source' => 'id_source']);
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
    public function getLemmaImages()
    {
        return $this->hasMany(LemmaImage::className(), ['id_lemma' => 'id_lemma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationLemmas()
    {
        return $this->hasMany(IllustrationLemma::className(), ['id_lemma' => 'id_lemma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrations()
    {
        return $this->hasMany(Illustration::className(), ['id_illustration' => 'id_illustration'])->viaTable('illustration_lemma', ['id_lemma' => 'id_lemma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLexArticle()
    {
        return $this->hasOne(LexArticle::className(), ['id_lemma' => 'id_lemma']);
    }

    public function getImagesUrl(){
        $img = [];
        foreach ($this->lemmaImages as $image){
            array_push($img, Yii::$app->urlManager->baseUrl .'/'. $image->url . $image->name);
        }
        return $img;
    }
}
