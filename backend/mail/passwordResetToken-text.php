<?php
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
    Hola <?= $user->username ?>,
   Siga el sigueinte hipervínculo para resetear su contraseña:
<?= $resetLink ?>