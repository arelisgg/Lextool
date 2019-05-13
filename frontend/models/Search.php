<?php

namespace frontend\models;

use Yii;



class Search extends \yii\db\ActiveRecord
{
    public $id_project;
    public $lemma;

    public function rules()
    {
        return [
            [['id_project', ], 'integer'],
            [['lemma', ], 'string'],
            //[['lemma', ], 'required', 'message' => ""],
        ];
    }

    public function attributeLabels()
    {
        return [

            'id_project' => 'Proyecto',
            'lemma' => 'Palabra',

        ];
    }

}