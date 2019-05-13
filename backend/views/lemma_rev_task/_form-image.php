<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Letter */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="lemma-form">
    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'id_project')->hiddenInput(['value' => $project->id_project])->label(false) ?>
            <?= $form->field($model, 'id_letter')->hiddenInput(['value' => $letter->id_letter])->label(false) ?>
            <?= $form->field($model, 'id_source')->hiddenInput(['value'=> $source->id_source])->label(false) ?>
            <?= $form->field($model, 'id_lemma_ext_plan')->hiddenInput(['value' => $ext_plan->id_lemma_ext_plan ])->label(false) ?>
            <?= $form->field($model, 'id_user')->hiddenInput(['value' => Yii::$app->user->id ])->label(false) ?>
            <input type="hidden" name="id_rev_plan" value="<?= $rev_plan->id_rev_plan ?>">

            <?= $form->field($model, 'extracted_lemma')->textInput(['required' => true]) ?>
            <?= $form->field($model, 'original_lemma')->textInput(['required' => true]) ?>

            <label class="control-label" for="elements">
                Elemento lexicográfico:
            </label>
            <select class="form-control margin-bottom-20" id="elements" name="substructure" required>
                <option value="<?= $model->substructure ?>"><?= $model->substructure ?></option>
                <?php
                foreach ($elements as $element) {
                    echo '<option id="'.$element->id_element_type.'" value="'.$element->name.'">'.$element->name.'</option>';
                }
                ?>
            </select>

            <input type="hidden" id="crop_x" name="x"/>
            <input type="hidden" id="crop_y" name="y"/>
            <input type="hidden" id="crop_w" name="w"/>
            <input type="hidden" id="crop_h" name="h"/>

            <input  id="image2" type="hidden" name="image_name" value="<?= $source->name ?>">
            <input id="img_url" type="hidden" name="image_url" value="<?= $source->url ?>">
            <input id="id_lemma" name="id_lemma" type="hidden" class="form-control" value="<?= $model->id_lemma ?>">

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                <a href="<?= Url::to(['lemma_rev_task/index', 'id_rev_plan' => $rev_plan->id_rev_plan])?>" class="btn btn-primary">
                    Finalizar edición
                </a>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row margin-top-10 margin-bottom-10">
        <div class="col-md-12">
            <div class="card-deck">
                <?php
                if (count($model->lemmaImages) > 0) {
                    foreach ($model->lemmaImages as $lemmaImage){
                        echo  ' <div class="card text-center" style="width: 18rem;" >
                                <img class="card-img " src="../../web/'.$lemmaImage->url.$lemmaImage->name.'" alt="'.$lemmaImage->name.'" >
                <div class="card-body">
                    <form action="image-delete?id='.$lemmaImage->id_lemma_image.'" method="post">
                       <input type="hidden" name="_csrf-backend" value="XWU9KezSScgx7EZqYH0RkepQIVfrM7imr4fY0gifwawbIX9fvoE5kXaqdg8GTETrhQJLb7pmi8zMsIm9TtONwA==">
                       <input type="hidden" name="id_rev_plan" value="'.$rev_plan->id_rev_plan.'">
                       <button class="btn btn-danger margin-bottom-10" type="submit">Eliminar</button>
                    </form>
                </div>
            </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 responsive-1024">
            <img  class="img-responsive" id="demo1" src="../../web/uploads/project/source/<?= $source->url?>">
        </div>
    </div>
</div>