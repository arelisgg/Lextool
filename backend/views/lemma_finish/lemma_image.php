<?php
/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */
?>

<h3>Imágenes extraidas</h3>
<div id="lightgallery" class="card-deck">
    <?php
    foreach ($model->lemmaImages as $image) {
        echo ' 
                        <div class="card bg-dark text-white" data-src="../../web/uploads/project/source_images/'.$image->name.'" style="padding: 0; box-shadow: 1px 2px 5px rgba(0,0,0,0.5);">
                           <a href="" onclick="closeModal()">
                             <img class="card-img" src="../../web/uploads/project/source_images/'.$image->name.'" alt="'.$image->name.'">
                           </a>
                        </div>
                ';
    }
    ?>
</div>
