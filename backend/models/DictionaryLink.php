<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "dictionary_link".
 *
 * @property int $id_link
 * @property string $name
 * @property string $link
 */
class DictionaryLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dictionary_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['name', 'link'], 'required'],
            [['link'], 'url'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_link' => 'Id Link',
            'name' => 'Nombre del diccionario',
            'link' => 'Enlace del diccionario',
        ];
    }
}
