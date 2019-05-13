<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Letter;

/* @var $this yii\web\View */
/* @var $model backend\models\Source */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="source-form">

    <?php $form = ActiveForm::begin([
        'id' => 'source-form',
        "options" => ["enctype" => "multipart/form-data"],
        //'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($model, 'id_project', ['options' => ['class' => 'hidden']])->textInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'url')->widget(FileInput::classname(), [
        'pluginOptions' => [
            'uploadUrl' => Url::to(['']),
            'previewFileType' => 'any',
            'initialPreviewAsData' => true,
            'purifyHtml' => true,
            'showPreview' => false,
            'showUpload' => false,
            'showRemove' => false,
            'initialPreview' => $model->isNewRecord || $model->url == 'null' ? '' : Url::home() . "uploads/project/source/". $model->url,
            'overwriteInitial' => false,
            'allowedExtensions' => ['jpg', 'jpeg', 'png', 'pdf'],
        ],
        'options'=>['accept'=>'.jpg,.jpeg,.png,.pdf',],
    ]) ?>

    <?= $form->field($model,'letter')->widget(Select2::className(),[
        'data' => ArrayHelper::map(Letter::find()->orderBy('letter')->all(),'id_letter','letter'),
        'options' => ['placeholder' => 'Seleccione...',],
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
        ],
    ]);
    ?>

    <?= $form->field($model, 'editable')->widget(Select2::classname(), [
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
    $('form#source-form').on('beforeSubmit', function(e)
    {
        var form = $('form#source-form');

        var data = new FormData(form.get(0));
        $.ajax({
            url: form.attr("action"),
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
        }).done(function(result) {
            if (result == "Error"){
                krajeeDialogError.alert("No se ha podido guardar, ha ocurrido un error.")
            } else {
                $(form).trigger("reset");
                $.pjax.reload({container: '#source-pjax'});
                $(document).find('#modal').modal('hide');
                krajeeDialogSuccess.alert('La fuente '+result+' ha sido guardada.');
            }
        }).fail(function() {
            krajeeDialogError.alert("Error")
        });
        return false;
    });
</script>