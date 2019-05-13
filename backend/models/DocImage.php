<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "doc_image".
 *
 * @property int $id_doc_image
 * @property int $id_document
 * @property string $name
 * @property string $url
 *
 * @property Document $document
 */
class DocImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_document'], 'default', 'value' => null],
            [['id_document'], 'integer'],
            [['name', 'url'], 'string'],
            [['id_document'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['id_document' => 'id_document']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_doc_image' => 'Id Doc Image',
            'id_document' => 'Id Document',
            'name' => 'Name',
            'url' => 'Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id_document' => 'id_document']);
    }
}
