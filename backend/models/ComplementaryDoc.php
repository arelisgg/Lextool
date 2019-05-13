<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "complementary_doc".
 *
 * @property int $id_complementary_doc
 * @property int $id_project
 * @property int $id_doc_type
 * @property string $name
 * @property string $url
 * @property bool $accepted
 *
 * @property DocType $docType
 * @property Project $project
 */
class ComplementaryDoc extends \yii\db\ActiveRecord
{
    public $doc_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'complementary_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_doc_type'], 'default', 'value' => null],
            [['id_project', 'id_doc_type'], 'integer'],
            [['name', 'url'], 'string'],
            [['accepted'], 'boolean'],
            [['id_doc_type'], 'exist', 'skipOnError' => true, 'targetClass' => DocType::className(), 'targetAttribute' => ['id_doc_type' => 'id_doc_type']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
            [['name', 'id_doc_type',], 'required'],
            [['url'], 'file', 'on' => 'create',
                'extensions' => 'pdf',
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
            'id_complementary_doc' => 'Id Complementary Doc',
            'id_project' => 'Id Project',
            'id_doc_type' => 'Documento complementario',
            'name' => 'Nombre',
            'url' => 'Archivo',
            'accepted' => 'Aprobado',
            'doc_name' => 'Documento complementario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocType()
    {
        return $this->hasOne(DocType::className(), ['id_doc_type' => 'id_doc_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id_project' => 'id_project']);
    }

    public function getSourceUrl()
    {
        $url = $this->url != "null" ?
            Yii::$app->urlManager->baseUrl . '/uploads/project/complementary_doc/' . $this->url :
            'No existe el fichero';
        return $url;
    }
}
