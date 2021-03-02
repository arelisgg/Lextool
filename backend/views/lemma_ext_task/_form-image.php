<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \backend\models\LemmaExtPlanTemplate;
use \backend\models\Letter;
use \backend\models\TemplateElement;
use \backend\models\LemmaCandExt;
use  \backend\models\ElementType;

/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */
/* @var $modelLemmasCand backend\models\LemmaCandExt */
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
            <?php $varString = "Lema"?>

            <?= Html::hiddenInput('substructure', $varString); ?>


            <div class="row">
                <div class="col-md-12 margin-bottom-20">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <?php
                            $i=0; $estos;
                            $ext_plan_id = $ext_plan->id_lemma_ext_plan;
                            $plan = \backend\models\LemmaExtPlanTemplate::find()->where(['id_ext_plan'=>$ext_plan_id])->one();
                            $template_id = \backend\models\Templates::find()->where(['id_template'=>$plan->id_template])->one();
                            $parametro=$template_id->id_template;
                            $tipoElemento[] = new ElementType();
                            $template_e = TemplateElement::findAll(['id_template' => $parametro]);
                            foreach ($template_e as $te):
                                $elements = \backend\models\Element::findAll(['id_element' =>  $te->id_element]);
                                foreach ($elements as $eel):
                                    $element_type = \backend\models\ElementType::findAll(['id_element_type' => $eel->id_element_type]);
                                    foreach ( $element_type as $temp){
                                        $tipoElemento[$i] = $temp;
                                        $active="";
                                        if ($i==0){$active="active";}
                                        echo '<li class="'.$active.'" >
                                           <a name="tab-'.$i.'" class="tab-clicked" href="#tab-'.$i.'" data-toggle="tab" >
                                                '.$temp->name.'
                                            </a>
                                        </li> ';

                                        $i++; $estos[$i]=$temp->id_element_type;
                                    }endforeach;
                            endforeach;
                            ?>
                        </ul>
                        <div class="tab-content">

                        <?php $r=0?>
                        <?php foreach($tipoElemento as $y => $var ):

                            $modelLemmasCand[$y] = new LemmaCandExt();

                            ?>

                            <div class="tab-pane fade <?php if($r == 0){echo 'in active';};?>" id="tab-<?= $r ?>">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h2 class="box-title"><i class="fa fa-language"></i> <?= $var->name ?></h2>
                                    </div>

                                    <div class="box-body" style="overflow-y: auto; padding: 0px;">
                                        <?php
                                        if ($r == 0){
                                            echo $form->field($model, 'extracted_lemma')->textInput(['required' => true]),
                                            $form->field($model, 'original_lemma')->textInput(['required' => true]);
                                        }
                                        echo
                                        $form->field($modelLemmasCand[$y], "[{$modelLemmasCand[$y]->id_element_type}]id_element_type")->hiddenInput(['value' => $var->id_element_type])->label(false),
                                        $form->field($modelLemmasCand[$y], "[{$y}]description")->textInput(['id'=>$var->id_element_type])
                                            ;
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php $r++; endforeach;?>
                        </div>
                    </div>
                </div>
            </div>



            <input type="hidden" id="crop_x" name="x"/>
            <input type="hidden" id="crop_y" name="y"/>
            <input type="hidden" id="crop_w" name="w"/>
            <input type="hidden" id="crop_h" name="h"/>

            <input  id="image2" type="hidden" name="image_name" value="<?= $source->name ?>">
            <input id="img_url" type="hidden" name="image_url" value="<?= $source->url ?>">
            <input id="id_lemma" name="id_lemma" type="hidden" class="form-control" value="<?= $model->id_lemma ?>">

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                <a href="<?= Url::to(['lemma_ext_task/index', 'id_ext_plan' => $ext_plan->id_lemma_ext_plan])?>" class="btn btn-primary">
                    Finalizar extracción
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
                        echo  ' <div class="card text-center">
                                <img class="card-img " src="../../web/'.$lemmaImage->url.$lemmaImage->name.'" alt="'.$lemmaImage->name.'" >
                <div class="card-body">
                    <form action="update-image-delete?id='.$lemmaImage->id_lemma_image.'" method="post">
                       <input type="hidden" name="_csrf-backend" value="XWU9KezSScgx7EZqYH0RkepQIVfrM7imr4fY0gifwawbIX9fvoE5kXaqdg8GTETrhQJLb7pmi8zMsIm9TtONwA==">
                       <button class="btn btn-danger margin-bottom-10" type="submit">Eliminar</button>
                    </form>                </div>
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

<script>

    function Ocultar(id){

    hola = document.getElementsByTagName("h2");
    hola.hide();
        tab = document.getElementsByClassName("active");
        tab.hide();


// aki termina mi infladera, empieza copia y pega

        const activeDiv = document.querySelector('.active');

        activeDiv.classList.add('hidden');                // Add the hidden class
        activeDiv.classList.remove('hidden');             // Remove the hidden class
        activeDiv.classList.toggle('hidden');             // Switch between hidden true and false
        activeDiv.classList.replace('active', 'warning');
    }



</script>