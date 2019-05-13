<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lex_article_review".
 *
 * @property int $id_lex_article_review
 * @property int $id_lex_article
 * @property int $id_review_criteria
 * @property string $comments
 *
 * @property LexArticle $lexArticle
 * @property ReviewCriteria $reviewCriteria
 */
class LexArticleReview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lex_article_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lex_article', 'id_review_criteria'], 'default', 'value' => null],
            [['id_lex_article', 'id_review_criteria'], 'integer'],
            [['id_review_criteria',], 'required'],
            [['comments'], 'string'],
            [['id_lex_article'], 'exist', 'skipOnError' => true, 'targetClass' => LexArticle::className(), 'targetAttribute' => ['id_lex_article' => 'id_lex_article']],
            [['id_review_criteria'], 'exist', 'skipOnError' => true, 'targetClass' => ReviewCriteria::className(), 'targetAttribute' => ['id_review_criteria' => 'id_review_criteria']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_lex_article_review' => 'Id Lex Article Review',
            'id_lex_article' => 'Id Lex Article',
            'id_review_criteria' => 'Criterio de revisión',
            'comments' => 'Comentario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLexArticle()
    {
        return $this->hasOne(LexArticle::className(), ['id_lex_article' => 'id_lex_article']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewCriteria()
    {
        return $this->hasOne(ReviewCriteria::className(), ['id_review_criteria' => 'id_review_criteria']);
    }
}
