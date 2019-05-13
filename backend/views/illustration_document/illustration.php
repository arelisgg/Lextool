<?php

/* @var $illustration backend\models\Illustration */

$extension = explode('.', $illustration->url);

//if ($extension[1] == "mp4" || $extension[1] == "avi" || $extension[1] == "mkv" || $extension[1] == "mpg"){
if ($extension[1] == "mp4"){

    echo '<video class="kv-preview-data file-preview-video" controls="" style="width:870px;height:430px;">
<source src="'.$illustration->getIllustrationDocumentUrl().'" type="video/'.$extension[1].'">
</video>';

} elseif ($extension[1] == "mp3"){

    echo '<audio class="kv-preview-data file-preview-audio" controls="" style="width: 870px;height:40px;">
<source src="'.$illustration->getIllustrationDocumentUrl().'" type="audio/'.$extension[1].'">
</audio>';

} elseif ($extension[1] == "jpg" || $extension[1] == "jpeg" || $extension[1] == "png" || $extension[1] == "gif"){

    echo '<img src="'.$illustration->getIllustrationDocumentUrl().'" style="width: 870px;height:430px;">';
}
