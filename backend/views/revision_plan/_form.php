<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\User;
use yii\helpers\ArrayHelper;
use backend\models\Letter;
use backend\models\SubModel;
use backend\models\ReviewCriteria;
use dosamigos\datepicker\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\RevisionPlan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="revision-plan-form">

    <?php $form = ActiveForm::begin([
        'id' => 'revision-plan-form',
    ]); ?>

    <?= $form->field($model, 'id_project', ['options' => ['class' => 'hidden']])->textInput() ?>

    <?= $form->field($model,'id_user')->widget(Select2::className(),[
        'data' => ArrayHelper::map(
            User::find()
                ->joinWith('userProjects')
                ->andWhere(['id_project' => $model->id_project,])
                ->andWhere("role = 'Jefe de Proyecto' OR role = 'Lexicógrafo'")
                ->orderBy('full_name')->all(),
            'id_user','full_name'),        'options' => ['placeholder' => 'Seleccione...'],
        'language' => 'es',
        'pluginOptions' => [
            'allowClear' => true,
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

    <?= $form->field($model,'letter')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Letter::find()->orderBy('letter')->all(),'id_letter','letter'),
        'options' => ['placeholder' => 'Seleccione...',],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
    ]);
    ?>

    <?= $form->field($model,'type')->widget(Select2::className(),[
        'data' => ['Normal'=>'Normal', 'Léxica'=>'Léxica'],
        'options' => [
                'placeholder' => 'Seleccione...',
            'onChange'=>'tipo()',
        ],
        'language' => 'es',
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);
    ?>

    <?= $form->field($model, 'edition')->widget(Select2::classname(), [
        'data' => [1=>'Sí', 0=>'No'],
        'options' => [
            'placeholder' => 'Seleccione...',
            'onChange'=>'edicion()',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'disabled' => $model->type == 'Normal' ? false : true,
    ]) ?>

    <?= $form->field($model,'criterias')->widget(Select2::className(),[
        'data' => ArrayHelper::map(ReviewCriteria::find()->where(['removed' => false])->orderBy('criteria')->all(),'id_review_criteria','criteria'),
        'options' => ['placeholder' => 'Seleccione...',],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
        'disabled' => $model->edition == 0 ? true : false,
    ]);
    ?>

    <?= $form->field($model,'submodel')->widget(Select2::className(),[
        'data' => ArrayHelper::map(
            SubModel::find()
                ->where(['id_project' => $model->id_project])->andWhere([">", "order", 0])
                ->orderBy('order')->all(),'id_sub_model','elementsName'),
        'options' => ['placeholder' => 'Seleccione...',],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
        'disabled' => $model->edition === 0 || $model->type == 'Normal' ? true : false,
    ]);
    ?>



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
    $('form#revision-plan-form').on('beforeSubmit', function(e)
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
                $.pjax.reload({container: '#revision-plan-pjax'});
                $(document).find('#modal').modal('hide');
                krajeeDialogSuccess.alert('La tarea de revisión ha sido '+result+'.');
            }
        }).fail(function() {
            krajeeDialogError.alert("Error")
        });
        return false;
    });
</script>