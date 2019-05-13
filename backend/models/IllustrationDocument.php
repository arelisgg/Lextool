<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "illustration_document".
 *
 * @property int $id_illustration_document
 * @property int $id_illustration
 * @property int $id_document
 * @property int $id_illustration_plan
 * @property boolean $reviewed
 *
 * @property ComplementaryDoc $document
 * @property Illustration $illustration
 * @property IllustrationPlan $illustrationPlan
 */
class IllustrationDocument extends \yii\db\ActiveRecord
{
    public $document_search;
    public $url;

    public $continue;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'illustration_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_illustration', 'id_document', 'id_illustration_plan'], 'default', 'value' => null],
            [['id_illustration', 'id_document', 'id_illustration_plan'], 'integer'],
            [['id_illustration', 'id_document', 'id_illustration_plan', 'continue'], 'required'],
            [['reviewed',], 'boolean'],
            [['id_document'], 'exist', 'skipOnError' => true, 'targetClass' => ComplementaryDoc::className(), 'targetAttribute' => ['id_document' => 'id_complementary_doc']],
            [['id_illustration'], 'exist', 'skipOnError' => true, 'targetClass' => Illustration::className(), 'targetAttribute' => ['id_illustration' => 'id_illustration']],
            [['id_illustration_plan'], 'exist', 'skipOnError' => true, 'targetClass' => IllustrationPlan::className(), 'targetAttribute' => ['id_illustration_plan' => 'id_illustration_plan']],
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
            'id_illustration_document' => 'Id Illustration Document',
            'id_illustration' => 'Ilustración',
            'id_document' => 'Documento complementario',
            'id_illustration_plan' => 'Plan de ilustración',
            'document_search' => 'Documento complementario',
            'url' => 'Archivo',
            'reviewed' => 'Aprobado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(ComplementaryDoc::className(), ['id_complementary_doc' => 'id_document']);
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

    public function getIllustrationUrl()
    {
        return $this->illustration->url != "null" ?
            Yii::$app->urlManager->baseUrl . '/uploads/project/illustration_document/' . $this->illustration->url :
            'No existe el fichero';
    }
}
