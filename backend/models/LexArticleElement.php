<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "lex_article_element".
 *
 * @property int $id_lex_article_element
 * @property int $id_lex_article
 * @property int $id_element
 * @property string $element
 * @property int $id_sub_element
 * @property int $order 
 * @property int $id_sub_model
 *
 * @property Element $element0
 * @property LexArticle $lexArticle
 * @property SubElement $subElement
 * @property SubModel $subModel
 */
class LexArticleElement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lex_article_element';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lex_article', 'id_element', 'id_sub_element', 'order', 'id_sub_model'], 'default', 'value' => null],
            [['id_lex_article', 'id_element', 'id_sub_element', 'order', 'id_sub_model'], 'integer'],
            [['element'], 'string'],
            [['id_element'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(), 'targetAttribute' => ['id_element' => 'id_element']],
            [['id_lex_article'], 'exist', 'skipOnError' => true, 'targetClass' => LexArticle::className(), 'targetAttribute' => ['id_lex_article' => 'id_lex_article']],
            [['id_sub_element'], 'exist', 'skipOnError' => true, 'targetClass' => SubElement::className(), 'targetAttribute' => ['id_sub_element' => 'id_sub_element']],
            [['id_sub_model'], 'exist', 'skipOnError' => true, 'targetClass' => SubModel::className(), 'targetAttribute' => ['id_sub_model' => 'id_sub_model']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_lex_article_element' => 'Id Lex Article Element',
            'id_lex_article' => 'Id Lex Article',
            'id_element' => 'Id Element',
            'element' => 'Element',
            'id_sub_element' => 'Id Sub Element',
            'order' => 'Order',
            'id_sub_model' => 'Id Sub Model',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement0()
    {
        return $this->hasOne(Element::className(), ['id_element' => 'id_element']);
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
    public function getSubElement()
    {
        return $this->hasOne(SubElement::className(), ['id_sub_element' => 'id_sub_element']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getSubModel()
    {
        return $this->hasOne(SubModel::className(), ['id_sub_model' => 'id_sub_model']);
    }
}
