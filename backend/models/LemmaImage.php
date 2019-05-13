<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lemma_image".
 *
 * @property int $id_lemma_image
 * @property int $id_lemma
 * @property string $name
 * @property string $url
 *
 * @property Lemma $lemma
 */
class LemmaImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lemma_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lemma'], 'default', 'value' => null],
            [['id_lemma'], 'integer'],
            [['name', 'url'], 'string'],
            [['id_lemma'], 'exist', 'skipOnError' => true, 'targetClass' => Lemma::className(), 'targetAttribute' => ['id_lemma' => 'id_lemma']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_lemma_image' => 'Id Lemma Image',
            'id_lemma' => 'Id Lemma',
            'name' => 'Name',
            'url' => 'Url',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemma()
    {
        return $this->hasOne(Lemma::className(), ['id_lemma' => 'id_lemma']);
    }
}
