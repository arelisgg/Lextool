<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Document */

\yii\web\YiiAsset::register($this);
?>
<div class="document-create">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1><?= $doc_type ?></h1>
            </div>
        </div>
        <?php
        foreach ($document_images as $document_image) {
            echo '<div class="row">
              <div class="col-md-12">
                  <img src="../web/'.$document_image->url.$document_image->name.'" alt="'.$document_image->name.'">
               </div>
         </div>';
        }
        ?>
    </div>
</div>
