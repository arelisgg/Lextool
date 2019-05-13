<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "illustration".
 *
 * @property int $id_illustration
 * @property string $name
 * @property string $url
 * @property int $id_project
 *
 * @property Project $project
 * @property IllustrationDocument[] $illustrationDocuments
 * @property IllustrationLemma[] $illustrationLemmas
 */
class Illustration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'illustration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'string'],
            [['name'], 'required'],
            [['id_project'], 'default', 'value' => null],
            [['id_project'], 'integer'],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
            [['url'], 'file', 'on' => 'create',
                'extensions' => 'jpg, jpeg, png, gif, mp3, mp4',
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
            'id_illustration' => 'Id Illustration',
            'name' => 'Nombre',
            'url' => 'Archivo',
            'id_project' => 'Id Project',
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
    public function getIllustrationDocuments()
    {
        return $this->hasMany(IllustrationDocument::className(), ['id_illustration' => 'id_illustration']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIllustrationLemmas()
    {
        return $this->hasMany(IllustrationLemma::className(), ['id_illustration' => 'id_illustration']);
    }

    public function getIllustrationUrl()
    {
        $url = $this->url != "null" ?
            Yii::$app->urlManager->baseUrl . '/uploads/project/illustration_lemma/' . $this->url :
            'No existe el fichero';
        return $url;
    }

    public function getIllustrationDocumentUrl()
    {
        $url = $this->url != "null" ?
            Yii::$app->urlManager->baseUrl . '/uploads/project/illustration_document/' . $this->url :
            'No existe el fichero';
        return $url;
    }
}
