<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "doc_make_plan".
 *
 * @property int $id_doc_make_plan
 * @property int $id_project
 * @property string $start_date
 * @property string $end_date
 * @property int $id_user
 * @property bool $finished
 * @property bool $late
 * @property string $docsName
 *
 * @property DocMakeDocument[] $docMakeDocuments
 * @property DocType[] $docTypes
 * @property Project $project
 * @property User $user
 */
class DocMakePlan extends \yii\db\ActiveRecord
{
    public $usuario;
    public $late_search;
    public $docs_search;
    public $docs;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc_make_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_user'], 'default', 'value' => null],
            [['id_project', 'id_user'], 'integer'],
            [['id_project', 'id_user', 'docs', 'start_date', 'end_date', 'finished'], 'required'],
            [['start_date', 'end_date'], 'safe'],
            [['finished'], 'boolean'],
            [['id_project'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['id_project' => 'id_project']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id_user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_doc_make_plan' => 'Id Doc Make Plan',
            'id_project' => 'Proyecto',
            'finished' => 'Finalizado',
            'start_date' => 'Fecha de inicio',
            'end_date' => 'Fecha de fin',
            'id_user' => 'Usuario',
            'usuario' => 'Usuario',
            'late' => 'Atrasado',
            'late_search' => 'Atrasado',
            'docsName' => 'Documentos',
            'docs' => 'Documentos',
            'docs_search' => 'Documentos',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocMakeDocuments()
    {
        return $this->hasMany(DocMakeDocument::className(), ['id_doc_make_plan' => 'id_doc_make_plan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocTypes()
    {
        return $this->hasMany(DocType::className(), ['id_doc_type' => 'id_doc_type'])->viaTable('doc_make_document', ['id_doc_make_plan' => 'id_doc_make_plan']);
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

    public function getLate()
    {
        if ($this->finished == false && $this->end_date < date('Y-m-d'))
            return "Sí";
        else
            return "No";
    }

    public function deleteAllPlansDocument()
    {
        foreach ($this->docMakeDocuments as $docs) {
            $docs->delete();
        }
    }


    public function getDocsName()
    {
        $docsName = "";
        for ($i = 0 ; $i < count($this->docTypes); $i++){
            if($i == 0)
                $docsName = $this->docTypes[$i]->name;
            else{
                $docsName = $docsName.', '.$this->docTypes[$i]->name;
            }
        }
        return $docsName;
    }

}
