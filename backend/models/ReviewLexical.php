<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "review_lexical".
 *
 * @property int $id_review_lexical
 * @property int $id_lex_article
 * @property string $word
 * @property string $comments
 *
 * @property LexArticle $lexArticle
 */
class ReviewLexical extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review_lexical';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lex_article'], 'default', 'value' => null],
            [['id_lex_article'], 'integer'],
            [['word', 'comments'], 'string'],
            [['word', 'comments'], 'required'],
            [['id_lex_article'], 'exist', 'skipOnError' => true, 'targetClass' => LexArticle::className(), 'targetAttribute' => ['id_lex_article' => 'id_lex_article']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_review_lexical' => 'Id Review Lexical',
            'id_lex_article' => 'Id Lex Article',
            'word' => 'Palabra',
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
}
