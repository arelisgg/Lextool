<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\User;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DateRangePicker;
use backend\models\Letter;
use backend\models\Source;
use backend\models\SemanticField;
use backend\models\Templates;
use  backend\models\TemplateType;

/* @var $this yii\web\View */
/* @var $model backend\models\LemmaExtPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lemma-ext-plan-form">

    <?php $form = ActiveForm::begin([
        'id' => 'lemma-ext-plan-form',
    ]); ?>

    <?= $form->field($model, 'id_project', ['options' => ['class' => 'hidden']])->textInput() ?>

    <?= $form->field($model,'id_user')->widget(Select2::className(),[
        'data' => ArrayHelper::map(
                    User::find()
                    ->joinWith('userProjects')
                    ->andWhere(['id_project' => $model->id_project,])
                    ->andWhere("role = 'Jefe de Proyecto' OR role = 'Lexicógrafo'")
                    ->orderBy('full_name')->all(),
                'id_user','full_name'),
        'options' => ['placeholder' => 'Seleccione...'],
        'language' => 'es',
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);
    ?>

    <?= $form->field($model,'semantic_field')->widget(Select2::className(),[
        'data' => ArrayHelper::map(SemanticField::find()->orderBy('name')->all(),'id_semantic_field','name'),
        'options' => ['placeholder' => 'Seleccione...',],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
    ]);
    ?>
    <?php
   $templates=Templates::find()->all();
   $result[]= new Templates();
   $i=0;
   foreach ($templates as $temps):
       $templateType = TemplateType::find()->where(['id_template_type'=> $temps->id_template_type])->one();
        $stage = $templateType->stage;
        if ($stage == "Extraccion"):
        $result[$i]=$temps;
         $i++;
          endif;
    endforeach;
    ?>

    <?= $form->field($model,'template')->widget(Select2::className(),[
        'data' => ArrayHelper::map($result,'id_template','name'),
        'options' => ['placeholder' => 'Seleccione...',],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
    ]);
    ?>

    <?= $form->field($model,'letter')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Letter::find()->orderBy('letter')->all(),'id_letter','letter'),
        'options' => ['placeholder' => 'Seleccione...',],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
    ]);
    ?>

    <?= $form->field($model,'source')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Source::find()->where(['id_project' => $model->id_project])->orderBy('name')->all(),'id_source','name'),
        'options' => ['placeholder' => 'Seleccione...',],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
    ]);
    ?>

    <?= $form->field($model, 'start_date')->widget(
        DateRangePicker::className(), [
        'attributeTo' => 'end_date',
        'form' =>$form,
        'language' => 'es',
        'labelTo' => '-',
        'clientOptions' =>[
            'todayHighlight' => true,
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
            'daysOfWeekDisabled' => '0.6',
            'minView' => 0,

        ]
    ])->label("Rango de fecha"); ?>

    <?= $model->isNewRecord ? "":
        $form->field($model, 'finished')->widget(Select2::classname(), [
            'data' => [1=>'Sí', 0=>'No'],
            'options' => [
                'placeholder' => 'Seleccione...',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>


    <div class="form-group" style="text-align: right">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $('form#lemma-ext-plan-form').on('beforeSubmit', function(e)
    {
        var form = $(this);
        $.post(
            form.attr("action"),
            form.serialize()
        ).done(function(result) {
            if (result == "Error"){
                krajeeDialogError.alert("No se ha podido agregar la tarea, ha ocurrido un error.")
            } else {
                $(form).trigger("reset");
                $.pjax.reload({container: '#lemma-ext-plan-pjax'});
                $(document).find('#modal').modal('hide');
                krajeeDialogSuccess.alert('La tarea de extracción ha sido '+result+'.');
            }
        }).fail(function() {
            krajeeDialogError.alert("Error")
        });
        return false;
    });
</script>