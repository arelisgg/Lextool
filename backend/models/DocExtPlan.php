<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "doc_ext_plan".
 *
 * @property int $id_doc_ext_plan
 * @property int $id_project
 * @property int $id_user
 * @property int $id_source
 * @property int $id_doc_type
 * @property string $start_date
 * @property string $end_date
 * @property bool $finished
 * @property string $ext_plan_name
 * @property bool $late
 *
 * @property DocType $docType
 * @property Project $project
 * @property User $user
 * @property Source $source
 * @property DocRevPlan[] $docRevPlans
 * @property Document[] $documents
 */
class DocExtPlan extends \yii\db\ActiveRecord
{
    public $usuario;
    public $doc_name;
    public $source_name;

    public $late_search;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_ext_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_source', 'id_doc_type', 'id_user'], 'default', 'value' => null],
            [['id_project', 'id_source', 'id_doc_type', 'id_user'], 'integer'],
            [['id_source', 'id_doc_type', 'id_user', 'start_date', 'end_date', 'finished'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['finished'], 'boolean'],
            [['id_doc_type'], 'exist', 'skipOnError' => true, 'targetClass' => DocType::className(), 'targetAttribute' => ['id_doc_type' => 'id_doc_type']],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
            [['id_source'], 'exist', 'skipOnError' => true, 'targetClass' => Source::className(), 'targetAttribute' => ['id_source' => 'id_source']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id_user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_doc_ext_plan' => 'Id Doc Ext Plan',
            'id_project' => 'Proyecto',
            'id_user' => 'Usuario',
            'id_source' => 'Fuente',
            'id_doc_type' => 'Tipo de documento',
            'finished' => 'Finalizado',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de fin',
            'usuario' => 'Usuario',
            'doc_name' => 'Tipo de documento',
            'source_name' => 'Fuente',
            'late' => 'Atrasado',
            'late_search' => 'Atrasado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocType()
    {
        return $this->hasOne(DocType::className(), ['id_doc_type' => 'id_doc_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id_project' => 'id_project']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id_user' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Source::className(), ['id_source' => 'id_source']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocRevPlans()
    {
        return $this->hasMany(DocRevPlan::className(), ['id_ext_doc_plan' => 'id_doc_ext_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['id_doc_ext_plan' => 'id_doc_ext_plan']);
    }

    public function getDocTypeName(){
        if ($this->id_doc_type != '')
            return $this->docType->name;
        else
            return "";
    }

    public function getSourceName(){
        if ($this->id_source != '')
            return $this->source->name;
        else
            return "";
    }

    public function getExt_plan_name()
    {
        return $this->user->full_name." - ".$this->docType->name." - ".$this->source->name;
    }

    public function getLate()
    {
        if ($this->finished == false && $this->end_date < date('Y-m-d'))
            return "Sí";
        else
            return "No";
    }
}
