<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Lemma;

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationLemma */

$this->title = 'Update Illustration Lemma: ' . $model->id_illustration_lemma;
$this->params['breadcrumbs'][] = ['label' => 'Illustration Lemmas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_illustration_lemma, 'url' => ['view', 'id' => $model->id_illustration_lemma]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="illustration-lemma-update">

    <?php $form = ActiveForm::begin([
        'id' => 'illustration-lemma-form',
        "options" => ["enctype" => "multipart/form-data"],
        //'enableAjaxValidation' => true,
    ]);
    $illustration = $model->illustration;
    $extension = explode('.', $illustration->url);

    $previewType = "";
    $initialPreviewConfig = [];
    //if ($extension[1] == "mp4" || $extension[1] == "avi" || $extension[1] == "mkv" || $extension[1] == "mpg"){
    if ($extension[1] == "mp4"){
        $previewType = "video";
        $initialPreviewConfig = [   'type' => 'video',
                                    'filetype'=>'video/mp4',
                                    'caption'=>'video.mp4',
                                    'url'=>false,
                                    //'key' => $illustration->id_illustration,
                                    'downloadUrl'=>false,
                                    'filename' => 'video.mp4'
                                ];



    } elseif ($extension[1] == "mp3"){
        $previewType = "audio";
        $initialPreviewConfig = [   'type' => 'audio',

                                    'filetype'=>'audio/mp3',
                                    'caption'=>'audio.mp3',
                                    'url'=>false,
                                    //'key' => $illustration->id_illustration,
                                    'downloadUrl'=>false,
                                    'filename' => 'audio.mp3'
                                ];


    } elseif ($extension[1] == "jpg" || $extension[1] == "jpeg" || $extension[1] == "png" || $extension[1] == "gif"){
        $previewType = "image";
        $initialPreviewConfig = [   'caption'=>'imagen',
                                    'url'=>false,
                                    //'key' => $illustration->id_illustration,
                                    'downloadUrl'=>false,
                                ];
    }


    ?>

    <?= $form->field($model, 'id_illustration_rev_plan', ['options' => ['class' => 'hidden']])->textInput() ?>
    <?= $form->field($model, 'id_illustration_plan', ['options' => ['class' => 'hidden']])->textInput() ?>

    <?= $form->field($model, 'id_letter', ['options' => ['class' => 'hidden']])->textInput() ?>

    <?= $form->field($model, 'id_lemma')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Lemma::find()->where(['id_project' => $model->illustrationPlan->id_project, 'id_letter' => $model->id_letter, 'lemario'=> true])->orderBy('extracted_lemma')->all(),'id_lemma','extracted_lemma'),
        'options' => [
            'placeholder' => 'Seleccione...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, "url")->widget(FileInput::classname(), [
        'pluginOptions' => [
            'uploadUrl' => Url::to(['']),
            'uploadAsync' => false,
            'overwriteInitial' => true,
            'initialPreview' => $illustration->getIllustrationUrl(),
            'initialPreviewAsData' => true,
            'initialPreviewFileType' => $previewType,
            'initialPreviewDownloadUrl' => $illustration->getIllustrationUrl(),
            'initialPreviewConfig' => [
                $initialPreviewConfig
            ],
            'purifyHtml' => true,

            'dropZoneTitle' => 'Archivo',
            'fileActionSettings' => [
                'showZoom' => true,
                'zoomClass' => 'kv-file-zoom btn btn-kv btn-default btn-outline-secondary margin-top-15',
                'showUpload' => false,
                'showRemove' => false,

            ],

            'previewFileType' => 'any',
            'showPreview' => true,
            'autoReplace' => true,
            'showUpload' => false,
            'showRemove' => false,
            /*'showCaption' => false,
            'browseClass' => 'photoBtnUpd',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' => ' Seleccionar Archivo',*/

            'allowedFileExtensions' => ['jpg', 'png', 'jpeg', 'gif', 'mp3', 'mp4',],
            'allowedPreviewTypes' => ["image", "video", "audio"],
        ],
        'options'=>['accept'=>'image/*,audio/*,video/*',],
    ]) ?>

    <div class="form-group" style="text-align: right">
        <?= Html::submitButton(!$model->reviewed ? 'Aprobar' : 'Desaprobar', [
            'class' => !$model->reviewed ? 'btn btn-primary' : 'btn btn-danger']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $('form#illustration-lemma-form').on('beforeSubmit', function(e)
    {
        var form = $('form#illustration-lemma-form');

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
                $.pjax.reload({container: '#illustration-lemma-pjax'});
                $(document).find('#modal').modal('hide');
                krajeeDialogSuccess.alert('La Ilustración de lema '+result+' ha sido guardada.');
            }
        }).fail(function() {
            krajeeDialogError.alert("Error")
        });
        return false;
    });
</script>