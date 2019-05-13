<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "source_letter".
 *
 * @property int $id_letter
 * @property int $id_source
 *
 * @property Letter $letter
 * @property Source $source
 */
class SourceLetter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'source_letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_letter', 'id_source'], 'required'],
            [['id_letter', 'id_source'], 'default', 'value' => null],
            [['id_letter', 'id_source'], 'integer'],
            [['id_letter', 'id_source'], 'unique', 'targetAttribute' => ['id_letter', 'id_source']],
            [['id_letter'], 'exist', 'skipOnError' => true, 'targetClass' => Letter::className(), 'targetAttribute' => ['id_letter' => 'id_letter']],
            [['id_source'], 'exist', 'skipOnError' => true, 'targetClass' => Source::className(), 'targetAttribute' => ['id_source' => 'id_source']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_letter' => 'Id Letter',
            'id_source' => 'Id Source',
        ];
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
    public function getSource()
    {
        return $this->hasOne(Source::className(), ['id_source' => 'id_source']);
    }
}
