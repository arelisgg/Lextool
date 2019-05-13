<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationLemma */
/* @var $form yii\widgets\ActiveForm */
/* @var $letters backend\models\Letter[] */
/* @var $lemmas backend\models\Lemma[] */
/* @var $modelIllustrations backend\models\Illustration[] */
/* @var $project backend\models\Project */


$this->title = 'Agregar ilustraciones';
$this->params['breadcrumbs'][] = ['label' => "Planes de ilustración" , 'url' => ['illustration/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => "Ilustraciones de lemas" , 'url' => ['illustration_lemma/index','id_illustration_plan' => $model->id_illustration_plan]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="illustration-lemma-create">

    <div class="illustration-lemma-form">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php
                foreach ($letters as $letter){
                    echo '<li class="list-clicked">
                            <a class="tab-clicked" href="#'.$letter->id_letter.'" data-toggle="tab" style="padding: 10px 11px;">
                                '.$letter->letter.'
                            </a>
                        </li>';
                }
                ?>
            </ul>
            <div class="tab-content">

                <?php $form = ActiveForm::begin([
                    'id' => 'illustration-lemma-form',
                    "options" => ["enctype" => "multipart/form-data"],

                ]); ?>
                <?= $form->field($model, 'id_illustration_plan', ['options' => ['class' => 'hidden']])->textInput() ?>
                <?= $form->field($model, 'id_letter', ['options' => ['class' => 'hidden']])->textInput() ?>
                <?= $form->field($model, 'continue', ['options' => ['class' => 'hidden']])->input('text') ?>


                <div class="tab-pane active">

                    <div class="row">
                        <div class="col-lg-3">
                            <div id="lemmas">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h2 class="box-title"><i class="fa fa-language"></i> Lemas</h2>
                                        <button id="select_all" type="button" class="btn btn-primary btn-sm pull-right" onclick="cambiar()">
                                            <i class="fa fa-check-square-o"></i>
                                        </button>
                                    </div>
                                    <div class="box-body" style="height: 675px; padding-left: 10px; overflow-y: auto; padding-bottom: 20px;">

                                        <?php
                                        for ($i = 0; $i < count($lemmas); $i++){
                                            echo '<div class="checkbox icheck">
                                                  <label class="margin-right-10"><input name="lemmas['.$i.']" type="checkbox">'.$lemmas[$i]->extracted_lemma.'</label>
                                              </div>';
                                        }

                                        ?>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9">

                            <?php DynamicFormWidget::begin([
                                'widgetContainer' => 'illustration_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                                'widgetBody' => '.illustration_items', // required: css class selector
                                'widgetItem' => '.illustration', // required: css class
                                'limit' => 200, // the maximum times, an element can be cloned (default 999)
                                'min' => 1, // 0 or 1 (default 1)
                                'insertButton' => '.add-item', // css class
                                'deleteButton' => '.remove-item', // css class
                                'model' => $modelIllustrations[0],
                                'formId' => 'illustration-lemma-form',
                                'formFields' => [
                                    'name',
                                    'url',
                                ],
                            ]); ?>
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h2 class="box-title" style="text-transform: capitalize"><i class="fa fa-file-image-o"></i> Ilustraciones</h2>
                                    <button type="button" class="add-item btn btn-success btn-sm pull-right">
                                        <i class="glyphicon glyphicon-plus"></i>
                                    </button>
                                </div>

                                <div class="box-body" style="height: 675px; padding-left: 10px; overflow-y: auto; padding-bottom: 20px;">
                                    <div class="illustration_items"><!-- widgetContainer -->
                                        <?php foreach ($modelIllustrations as $i => $modelIllustration): ?>
                                            <div class="illustration"><!-- widgetBody -->
                                                <?php
                                                // necessary for update action.
                                                if (!$modelIllustration->isNewRecord) {
                                                    echo Html::activeHiddenInput($modelIllustration, "[{$i}]id_illustration");
                                                }else
                                                    $modelIllustration->scenario = 'create';
                                                ?>

                                                <div class="col-lg-4" style="padding-left: 0px; padding-right: 0px; height: 330px">

                                                    <?= $form->field($modelIllustration, "[{$i}]url")->widget(FileInput::classname(), [
                                                        'pluginOptions' => [
                                                            'uploadUrl' => Url::to(['']),
                                                            'dropZoneTitle' => 'Ilustración',
                                                            'previewFileType' => 'any',
                                                            'purifyHtml' => true,
                                                            'showPreview' => true,
                                                            'fileActionSettings' => [
                                                                'showZoom' => true,
                                                                'zoomClass' => 'kv-file-zoom btn btn-kv btn-default btn-outline-secondary margin-top-15',
                                                                'showUpload' => false,
                                                                //'showRemove' => false,

                                                            ],
                                                            'autoReplace' => true,
                                                            'uploadAsync' => true,
                                                            /*'initialPreviewConfig' => [
                                                               ['url' => 'delete_image', 'key' => $model->id_project],
                                                           ],*/
                                                            'showUpload' => false,
                                                            'showRemove' => false,
                                                            'showCaption' => false,
                                                            'browseClass' => 'photoBtnUpd',
                                                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                                                            'browseLabel' => ' Seleccionar ilustración',
                                                            'overwriteInitial' => true,
                                                            'initialPreviewAsData' => true,
                                                            'initialPreview' => $modelIllustration->isNewRecord || $modelIllustration->url == 'null' ? '' : Url::home() . "uploads/project/illustration_lemma/". $modelIllustration->url,
                                                            'allowedFileExtensions' => ['jpg', 'png', 'jpeg', 'gif', 'mp3', 'mp4',],
                                                            'allowedPreviewTypes' => ['image','video','audio'],
                                                        ],
                                                        //'options'=>['accept'=>'image/*,audio/*,video/*',],
                                                        'options'=>['accept'=>'.jpg,.jpeg,.png,.gif,.mp3,.mp4',],

                                                    ]) ?>


                                                    <button  style="display: none" type="button" class="remove-item" >Eliminar</button>
                                                    <button  type="button" class="eliminar btn btn-danger btn-xs" onclick="deleteForm($(this))"><i class="glyphicon glyphicon-minus"></i></button>

                                                </div>

                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php DynamicFormWidget::end(); ?>

                        </div>
                    </div>
                </div>

                <div class="form-group" style="text-align: right">
                    <?= Html::submitButton( 'Guardar', [
                        'class' => 'btn btn-success',
                        'onclick' => "selectedLemma()"]) ?>
                    <a href="<?= Url::toRoute(["illustration_lemma/index", 'id_illustration_plan' => $model->id_illustration_plan]) ?>" type="button" class="btn btn-default" >Cancelar</a>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>
