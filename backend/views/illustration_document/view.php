<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\IllustrationDocument */

$this->title = $model->id_illustration_document;
$this->params['breadcrumbs'][] = ['label' => 'Illustration Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="illustration-document-view">

    <h3><strong>Documento complementario: </strong><?= $model->document->docType->name?></h3>
    <?php
    $extension = explode('.', $model->illustration->url);

    //if ($extension[1] == "mp4" || $extension[1] == "avi" || $extension[1] == "mkv" || $extension[1] == "mpg"){
    if ($extension[1] == "mp4"){

        echo '<video class="kv-preview-data file-preview-video" controls="" style="width:870px;height:430px;">
        <source src="'.$model->illustration->getIllustrationDocumentUrl().'" type="video/'.$extension[1].'">
        </video>';

    } elseif ($extension[1] == "mp3"){

        echo '<audio class="kv-preview-data file-preview-audio" controls="" style="width: 870px;height:40px;">
        <source src="'.$model->illustration->getIllustrationDocumentUrl().'" type="audio/'.$extension[1].'">
        </audio>';

    } elseif ($extension[1] == "jpg" || $extension[1] == "jpeg" || $extension[1] == "png" || $extension[1] == "gif"){

        echo '<img src="'.$model->illustration->getIllustrationDocumentUrl().'" style="width: 870px;height:430px;">';
    }

    ?>

    <p style="text-align: right; margin-top: 20px">
        <?= Html::button('Editar', [
            "onclick"=>"actionUpdate('$model->id_illustration_document', '".Url::to(['/illustration_document/update',])."')",
            "title"=>"Editar",
            'class' => 'btn btn-primary']) ?>
        <?= Html::button('Eliminar', [
            "onclick"=>"actionDelete('$model->id_illustration_document', '".Url::to(['/illustration_document/delete',])."')",
            "title"=>"Eliminar",
            'class' => 'btn btn-danger',
        ]) ?>

    </p>

</div>
