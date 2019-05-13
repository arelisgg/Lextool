<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lex_article".
 *
 * @property int $id_lex_article
 * @property int $id_lemma
 * @property string $article
 * @property bool $finished
 * @property bool $reviewed
 *
 * @property Lemma $lemma
 * @property LexArticleElement[] $lexArticleElements
 * @property LexArticleReview[] $lexArticleReviews
 * @property ReviewLexical[] $reviewLexicals
 */
class LexArticle extends \yii\db\ActiveRecord
{
    public $letter;
    public $lemma_search;
    public $id_project;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lex_article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lemma'], 'default', 'value' => null],
            [['id_lemma'], 'integer'],
            [['article'], 'string'],
            [['finished', 'reviewed'], 'boolean'],
            [['id_lemma'], 'exist', 'skipOnError' => true, 'targetClass' => Lemma::className(), 'targetAttribute' => ['id_lemma' => 'id_lemma']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_lex_article' => 'Id Lex Article',
            'id_lemma' => 'Lema ',
            'article' => 'Artículo',
            'finished' => 'Finalizado',
            'reviewed' => 'Aprobado',
            'letter' => 'Letra',
            'lemma_search' => 'Lema',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLemma()
    {
        return $this->hasOne(Lemma::className(), ['id_lemma' => 'id_lemma']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLexArticleElements()
    {
        return $this->hasMany(LexArticleElement::className(), ['id_lex_article' => 'id_lex_article']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLexArticleReviews()
    {
        return $this->hasMany(LexArticleReview::className(), ['id_lex_article' => 'id_lex_article']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewLexicals()
    {
        return $this->hasMany(ReviewLexical::className(), ['id_lex_article' => 'id_lex_article']);
    }

    public function getFinish(){
        return $this->finished ? "Si" : "No";
    }

    public function getReview(){
        return $this->reviewed ? "Si" : "No";
    }
}
