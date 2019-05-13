<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Document */

\yii\web\YiiAsset::register($this);
?>
<div class="document-create">
    <div class="container-fluid">
        <div class="row padding-tb-15">
            <div class="col-md-12">
                <h1><?= $model->docType->name ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p><?= $model->original_text ?></p>
            </div>
        </div>
    </div>
</div>
