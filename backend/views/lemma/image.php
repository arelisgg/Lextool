<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */
?>


<div id="app" class="lemma-index">
    <section id="demos">
        <div class="row">
            <div class="large-12 columns">
                <div class="owl-carousel owl-theme">
                    <?php
                        foreach ($model->getImagesUrl() as $img){
                            echo '<div class="item" style="padding: 0px"><img src="'.$img.'"> </div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('.owl-carousel').owlCarousel({
            items: 3,
            autoHeight: false,
            margin: 10,
            stagePadding: 25,
            autoWidth: false,
        });
    });
</script>
