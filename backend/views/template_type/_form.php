<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TemplateType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="template-type-form">

    <?php $form = ActiveForm::begin([
        'id' => 'template-type-form'
    ]); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'stage')->dropDownList(['Redaccion'=>'Redacción','Extraccion'=>'Extración'],['prompt'=>'-Seleccione una opción-']) ?>

    <div class="form-group" style="text-align: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $('form#template-type-form').on('beforeSubmit', function(e)
    {
        var form = $(this);
        $.post(
            form.attr("action"),
            form.serialize()
        ).done(function(result) {
            if (result == "Error") {
                krajeeDialogError.alert("No se ha podido guardar, ha ocurrido un error.")
            } else {
                $(form).trigger("reset");
                $.pjax.reload({container: '#template-type-pjax'});
                $(document).find('#modal').modal('hide');
                krajeeDialogSuccess.alert('El tipo de plantilla "'+result+'" ha sido guardado.');
            }
        }).fail(function() {
            krajeeDialogError.alert("Error")
        });
        return false;
    });
</script>