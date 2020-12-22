<?php
/**
 * Created by PhpStorm.
 * User: Leo
 * Date: 19/12/2020
 * Time: 16:21
 */

namespace backend\models;
/**
 * This is the model class for table "lemma_cand_ext".
 *
 * @property int $id_lemma_cand_ext
 * @property int $id_lemma
 * @property int $id_element_type
 * @property string $description
 * @property int $id_sub_element
 * @property int $order
 * @property int $number
 *
 * @property Element $element0
 * @property Lemma $lemma
 * @property SubElement $subElement
 *
 */
class LemmaCandExt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lemma_cand_ext';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'id_lemma', 'id_element_type', 'id_sub_element', 'order', 'number'], 'default', 'value' => null],
            [[ 'id_lemma', 'id_element_type', 'id_sub_element', 'order', 'number'], 'integer'],
            [['description'], 'string'],
            [['id_element_type'], 'exist', 'skipOnError' => true, 'targetClass' => ElementType::className(), 'targetAttribute' => ['id_element_type' => 'id_element_type']],
            [['id_lemma'], 'exist', 'skipOnError' => true, 'targetClass' => Lemma::className(), 'targetAttribute' => ['id_lemma' => 'id_lemma']],
            [['id_sub_element'], 'exist', 'skipOnError' => true, 'targetClass' => SubElement::className(), 'targetAttribute' => ['id_sub_element' => 'id_sub_element']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_lemma_cand_ext' => 'Id Lemma candidato extraido',
            'id_lemma' => 'Id Lemma',
            'id_element_type' => 'Id Element type',
            'description' => 'Descripción',
            'id_sub_element' => 'Id Sub Element',
            'order' => 'Order',
            'number' => 'Número',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementType0()
    {
        return $this->hasOne(ElementType::className(), ['id_element_type' => 'id_element_type']);
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
    public function getSubElement()
    {
        return $this->hasOne(SubElement::className(), ['id_sub_element' => 'id_sub_element']);
    }



}