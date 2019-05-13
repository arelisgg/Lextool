<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'LexTool';
?>
<div class="site-index">

    <div class="jumbotron" style="margin-top: 120px; background-color: #fff;">
        <h1>LexTool</h1>
        <?php $form = ActiveForm::begin([
            'id' => 'search-form',
            'method' => 'get',
            'action' => ['search'],
        ]); ?>

        <div class="form-group field-search-lemma required" style="display: inline-flex; width: 65%; margin-left: 20px">

            <input type="text" id="search-lemma" class="form-control" name="Search[lemma]" placeholder="Escriba una palabra para buscar" aria-required="true" aria-invalid="false">

            <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i>', ['class' => 'btn btn-primary', 'style' => 'margin-left: 2px']) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
