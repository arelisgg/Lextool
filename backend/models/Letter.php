<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "letter".
 *
 * @property int $id_letter
 * @property string $letter
 *
 * @property Lemma[] $lemmas
 * @property LemmaExtPlanLetter[] $lemmaExtPlanLetters
 * @property LemmaExtPlan[] $extPlans
 * @property SourceLetter[] $sourceLetters
 * @property Source[] $sources
 */
class Letter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['letter'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_letter' => 'Id Letter',
            'letter' => 'Letter',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmas()
    {
        return $this->hasMany(Lemma::className(), ['id_letter' => 'id_letter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemmaExtPlanLetters()
    {
        return $this->hasMany(LemmaExtPlanLetter::className(), ['id_letter' => 'id_letter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExtPlans()
    {
        return $this->hasMany(LemmaExtPlan::className(), ['id_lemma_ext_plan' => 'id_ext_plan'])->viaTable('lemma_ext_plan_letter', ['id_letter' => 'id_letter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceLetters()
    {
        return $this->hasMany(SourceLetter::className(), ['id_letter' => 'id_letter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSources()
    {
        return $this->hasMany(Source::className(), ['id_source' => 'id_source'])->viaTable('source_letter', ['id_letter' => 'id_letter']);
    }
}
