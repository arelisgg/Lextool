<?php

use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<div class="password-reset">
    <p>Hola <?= Html::encode($user->username) ?>,</p>
    <p>Siga el siguiente hipervínculo para resetear su contraseña:</p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>