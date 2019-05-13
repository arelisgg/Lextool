<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ElementType;

/* @var $this yii\web\View */
/* @var $model backend\models\ElementSubType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="element-sub-type-form">

    <?php $form = ActiveForm::begin([
        'id' => 'element-sub-type-form'
    ]); ?>

    <?= $form->field($model, 'id_element_type')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(ElementType::find()->where(['removed' => false])->all(), 'id_element_type', 'name'),
        'options' => [
            'placeholder' => 'Seleccione...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <div class="form-group" style="text-align: right;">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Editar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $('form#element-sub-type-form').on('beforeSubmit', function(e)
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
                $.pjax.reload({container: '#element-sub-type-pjax'});
                $(document).find('#modal').modal('hide');
                krajeeDialogSuccess.alert('El tipo de sub-elemento lexicográfico "'+result+'" ha sido guardado.');
            }
        }).fail(function() {
            krajeeDialogError.alert("Error")
        });
        return false;
    });
</script>