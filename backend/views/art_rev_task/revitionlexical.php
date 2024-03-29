<?php
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $project backend\models\Project */
/* @var $model backend\models\Lemma */
/* @var $lex_aricle backend\models\LexArticle */
/* @var $revision_plan backend\models\RevisionPlan */
/* @var $reviewLexicals backend\models\ReviewLexical[] */


$this->title = 'Revisión léxica: '.$model->extracted_lemma;
$this->params['breadcrumbs'][] = ['label' => "Planes de revisión" , 'url' => ['art_rev_task/plans','id_project' => $project->id_project]];
$this->params['breadcrumbs'][] = ['label' => "Lemas" , 'url' => ['art_rev_task/indexlexical','id_revision_plan' => $revision_plan->id_revision_plan]];
$this->params['breadcrumbs'][] = $this->title;


Modal::begin([
    'header' => '<h3>Buscar</h3>',
    'id' => 'modal',
    'size' => 'modal-lg',
    'options' => [
        'tabindex' => false
    ],
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>
<div id="id_project" class="hidden"><?=$project->id_project?></div>
<div id="name_project" class="hidden"><?=$project->name?></div>
<div class="box box-primary">
    <div class="box-header with-border">
        <h2 class="box-title"><i class="fa fa-language"></i> <?=$model->extracted_lemma?></h2>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-12">

                <div id="lex-article" class="form-group">
                    <?=$model->lexArticle->article?>
                </div>
            </div>
        </div>
        <!--<button type="button" onclick="actionSearch('72', '')" class="btn btn-info">Buscar</button>-->
        <hr>

        <div class="row">
            <div class="col-lg-12">
                <p><strong>Componentes:</strong> <?=$revision_plan->getSubmodelName()?></p>
            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'review-form',
            "method" => "post",
            //'enableAjaxValidation' => true,
        ]); ?>

        <?= $form->field($lex_aricle, 'reviewed')->checkbox() ?>

        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'review_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
            'widgetBody' => '.review_items', // required: css class selector
            'widgetItem' => '.review', // required: css class
            'limit' => 200, // the maximum times, an element can be cloned (default 999)
            'min' => 0, // 0 or 1 (default 1)
            'insertButton' => '.add-item', // css class
            'deleteButton' => '.remove-item', // css class
            'model' => $reviewLexicals[0],
            'formId' => 'review-form',
            'formFields' => [
                'id_review_criteria',
                'comments',
            ],
        ]); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h2 class="box-title" ><i class="fa fa-file-image-o"></i> Observaciones</h2>
                <button type="button" class="add-item btn btn-success btn-sm pull-right">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </div>

            <div class="box-body" style="min-height: 188px;">
                <div class="review_items"><!-- widgetContainer -->
                    <?php foreach ($reviewLexicals as $i => $reviewLexical): ?>
                        <div class="review"><!-- widgetBody -->
                            <?php
                            // necessary for update action.
                            if (!$reviewLexical->isNewRecord) {
                                echo Html::activeHiddenInput($reviewLexical, "[{$i}]id_review_lexical");
                            }
                            ?>
                            <div class="col-lg-11" style="padding-left: 0px; padding-right: 0px">
                                <?= $form->field($reviewLexical, "[{$i}]word")->textInput(['class' => 'form-control word']) ?>
                                <?= $form->field($reviewLexical, "[{$i}]comments")->textarea(['rows' => 3]) ?>
                            </div>

                            <div class="col-lg-1"  style="padding-left: 0px; padding-right: 0px; margin-top: 30px; text-align: center">
                                <button  style="display: none" type="button" class="remove-item" >Eliminar</button>
                                <button  type="button" class="eliminar btn btn-danger btn-xs" onclick="deleteForm($(this))">Eliminar</button>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php DynamicFormWidget::end(); ?>


        <div class="form-group" style="text-align: right">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-primary']) ?>
            <a href="<?= Url::to(['art_rev_task/indexlexical', 'id_revision_plan' => $revision_plan->id_revision_plan]) ?>" type="button" class="btn btn-default" >Cancelar</a>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>