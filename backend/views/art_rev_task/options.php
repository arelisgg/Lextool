<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Lemma */
/* @var $illustrations backend\models\Illustration[] */

?>

<div class="lemma-form" style="padding: 10px">

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label>Diccionario</label>
                <div><?=$model->project->name?></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <div><?=$model->lexArticle->article?></div>
            </div>
        </div>
    </div>

    <label><?=count($illustrations)>0?"Ilustraciones":""?></label>
    <section id="demos">
        <div class="row">
            <div class="large-12 columns">
                <div class="owl-carousel owl-theme">
                    <?php

                    foreach ($illustrations as $illustration){
                        $extension = explode('.', $illustration->url);
                        if ($extension[1] == "mp4"){
                            echo ' 
                                    <div class="item" style="padding: 5px">
                                        <div class="card" style="width: 100%">
                                            
                                            <div class="card-body" style="padding: 0px">
                                                <video class="card-img-top" controls="" style="width:184px;height:140px;"> 
                                                    <source src="../../../backend/web/uploads/project/illustration_lemma/'.$illustration->url.'" type="video/'.$extension[1].'">
                                                </video>
                                            </div>
                                        </div>  
                                    </div>
                                    ';

                        } elseif ($extension[1] == "mp3"){
                            echo ' 
                                    <div class="item" style="padding: 5px">
                                        <div class="card" style="width: 100%">
                                            
                                            <div class="card-body" style="padding: 0px">
                                                <audio class="card-img-top" controls="" style="width:184px;height:40px; margin: 30px 0px 20px 0px">
                                                    <source src="../../../backend/web/uploads/project/illustration_lemma/'.$illustration->url.'" type="audio/'.$extension[1].'">
                                                </audio>
                                            </div>
                                        </div>  
                                    </div>
                                    ';

                        } elseif ($extension[1] == "jpg" || $extension[1] == "jpeg" || $extension[1] == "png" || $extension[1] == "gif"){

                            echo ' 
                                    <div class="item" style="padding: 5px">
                                        <div class="card" style="width: 100%">
                                            
                                            <div class="card-body" style="padding: 0px">
                                                <img class="card-img-top" src="../../../backend/web/uploads/project/illustration_lemma/'.$illustration->url.'" alt="'.$illustration->url.'" style="width: 184px;height:140px;">
                                            </div>
                                        </div>  
                                    </div>
                                    ';
                        }
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
