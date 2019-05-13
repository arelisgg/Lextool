<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\User;
use backend\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model backend\models\UserProject */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-project-form">

    <?php $form = ActiveForm::begin([
        'id' => 'user-project-form',
        //'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($model, 'id_project',['options' => ['class' => 'hidden']])->textInput() ?>

    <?= $form->field($model,'id_user')->widget(Select2::className(),[
        'data' => ArrayHelper::map(User::find()->joinWith('authAssignments')
            ->where("(item_name = 'Jefe de Proyecto' OR item_name = 'Especialista') AND enabled = true")
            ->orderBy('full_name')->all(),
            'id_user','full_name'),
        'options' => ['placeholder' => 'Seleccione...'],
        'language' => 'es',
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);
    ?>

    <?= $form->field($model,'role')->widget(Select2::className(),[
        'data' => [
            'Jefe de Proyecto' => 'Jefe de Proyecto',
            'Lexicógrafo' => 'Lexicógrafo',
            'Colaborador' => 'Colaborador',
        ],
        'options' => ['placeholder' => 'Seleccione...'],
        'language' => 'es',
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]);
    ?>

    <div class="form-group" style="text-align: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $('form#user-project-form').on('beforeSubmit', function(e)
    {
        var form = $(this);
        $.post(
            form.attr("action"),
            form.serialize()
        ).done(function(result) {
            if (result == "Exist") {
                krajeeDialogError.alert("No se ha pododo agregar. En este proyecto ya existe el usuario con el mismo rol.")
            } else if (result == "Error"){
                krajeeDialogError.alert("No se ha podido guardar, ha ocurrido un error.")
            } else if (result == "Boss"){
                krajeeDialogError.alert("No se ha podido guardar, ya existe un jefe de proyecto.")
            } else {
                $(form).trigger("reset");
                $.pjax.reload({container: '#user-project-pjax'});
                $(document).find('#modal').modal('hide');
                krajeeDialogSuccess.alert('El usuario ha sido '+result+'.');
            }
        }).fail(function() {
            krajeeDialogError.alert("Error")
        });
        return false;
    });
</script>
