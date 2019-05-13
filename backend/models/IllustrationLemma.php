<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "illustration_lemma".
 *
 * @property int $id_illustration_lemma
 * @property int $id_illustration
 * @property int $id_lemma
 * @property int $id_illustration_plan
 * @property boolean $reviewed
 *
 * @property Illustration $illustration
 * @property IllustrationPlan $illustrationPlan
 * @property Lemma $lemma
 */
class IllustrationLemma extends \yii\db\ActiveRecord
{
    public $id_letter;
    public $letter;
    public $lemma_search;
    public $illustration_search;

    public $lemmas;
    public $url;

    public $id_illustration_rev_plan;

    public $continue;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'illustration_lemma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_illustration', 'id_lemma', 'id_illustration_plan'], 'default', 'value' => null],
            [['id_illustration', 'id_lemma', 'id_illustration_plan'], 'integer'],
            [['id_illustration', 'id_lemma', 'id_illustration_plan', 'continue'], 'required'],
            [['reviewed',], 'boolean'],
            [['id_illustration'], 'exist', 'skipOnError' => true, 'targetClass' => Illustration::className(), 'targetAttribute' => ['id_illustration' => 'id_illustration']],
            [['id_illustration_plan'], 'exist', 'skipOnError' => true, 'targetClass' => IllustrationPlan::className(), 'targetAttribute' => ['id_illustration_plan' => 'id_illustration_plan']],
            [['id_lemma'], 'exist', 'skipOnError' => true, 'targetClass' => Lemma::className(), 'targetAttribute' => ['id_lemma' => 'id_lemma']],
            [['url'], 'file',
                'extensions' => 'jpg, jpeg, png, gif, mp3, mp4',
                'skipOnEmpty' => true,
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
            'id_illustration_lemma' => 'Id Illustration Lemma',
            'id_illustration' => 'Ilustración',
            'url' => 'Archivo',
            'illutration_search' => 'Ilustración',
            'id_lemma' => 'Lema',
            'lemma_search' => 'Lema',
            'id_illustration_plan' => 'Id Illustration Plan',
            'reviewed' => 'Aprobado',
            'id_letter' => 'Letra',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustration()
    {
        return $this->hasOne(Illustration::className(), ['id_illustration' => 'id_illustration']);
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
    public function getLemma()
    {
        return $this->hasOne(Lemma::className(), ['id_lemma' => 'id_lemma']);
    }

    public function getIllustrationUrl()
    {
        return $this->illustration->url != "null" ?
            Yii::$app->urlManager->baseUrl . '/uploads/project/illustration_lemma/' . $this->illustration->url :
            'No existe el fichero';
    }

}
