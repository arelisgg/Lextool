<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\models\Project;


/* @var $lemmas backend\models\Lemma[] */
?>

<div class="lemma-search">
    <div class="row">
        <div class="col-lg-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2 class="box-title"><i class="fa fa-language"></i> Lemas</h2>
                </div>
                <div class="box-body" style="overflow-y: auto; padding: 10px 10px 0px 10px; text-align: center">
                    <?php $form = ActiveForm::begin([
                        'id' => 'search-form',
                        'method' => 'get',
                        'action' => ['search'],
                    ]); ?>

                    <?= $form->field($model, 'id_project', ['options' => ['class' => 'hidden']])->textInput() ?>

                    <div class="form-group field-search-lemma required" style="display: flex">

                        <input type="text" id="search-lemma" class="form-control" name="Search[lemma]" value="<?=$model->lemma?>" placeholder="Palabra" aria-required="true" aria-invalid="false">

                        <button type="button" onclick="actionReSearch()" class="btn btn-primary" style="margin-left: 2px"><i class="glyphicon glyphicon-search"></i></button>

                    </div>

                    <?php ActiveForm::end(); ?>
                    <div id="lemas">
                        <select multiple class="form-control" style="height: 339px">
                            <?php
                            foreach ($lemmas as $lemma){
                                echo '<option id="'.$lemma->id_lemma.'" onclick="options('.$lemma->id_lemma.')">'.$lemma->extracted_lemma.'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <button class="btn btn-success" type="button" onclick="addComment()" style="margin-top: 8px; margin-bottom: 8px;">
                        <span class="fa fa-plus"></span> Añadir observación
                    </button>
                </div>

            </div>
        </div>
        <div class="col-lg-9" style="padding-left: 0px">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2 class="box-title"><i class="fa fa-gears"></i> Opciones</h2>
                </div>
                <div id="options" class="box-body" style="overflow-y: auto; padding: 0px 5px 0px 5px;">

                </div>
            </div>
        </div>
    </div>
</div>
