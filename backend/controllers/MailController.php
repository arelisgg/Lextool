<?php

namespace backend\controllers;



use common\models\User;
use yii\console\Controller;
use Yii;
use yii\helpers\ArrayHelper;

class MailController extends Controller
{

    public static function sendActionUser($accion, $usuario){
        $sended = true;
        try{
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($usuario->email)
                ->setSubject('Lextool - '.$accion)
                ->setTextBody('Se le informa que el usuario "'.$usuario->username.'" ha sido '.$accion)
                ->send();
        }catch (\Exception $e){
            Yii::$app->session->setFlash('error', 'No se pudo enviar el correo de confirmación');
            $sended = false;
        }
        return $sended;
    }

    /**
    Para cuando el usuario se registre que le llegue un correo diciendo que espere a su habilitacion
     */

    public static function sendRegisterUser($accion, $usuario){
        $sended = true;
        try{
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($usuario->email)
                ->setSubject('Lextool - '.$accion)
                ->setTextBody('Se le informa que su usuario "'.$usuario->username.'" ha sido '.$accion.'. Espere a ser notificado por su habilitación.')
                ->send();
        }catch (\Exception $e){
            //Yii::$app->session->setFlash('error', 'No se pudo enviar el correo de confirmación');
            $sended = false;
        }
        return $sended;
    }

    /**
    Para notificarle a los administradores cuando un usuario se registre
     */

    public static function sendToAdminRegisterUser($usuario){
        $usuarios = ArrayHelper::getColumn(User::find()->joinWith('authAssignments')->where(['item_name'=>'Administrador'])->all(), 'email');
        $sended = true;
        try{
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setTo($usuarios)
                ->setSubject('Lextool - Nuevo usuario registrado')
                ->setTextBody('Se le informa que el usuario "'.$usuario->username.'" se ha registrado en el sitio')
                ->send();
        }catch (\Exception $e){
            //ToDo: Something to do if mail can not be send
            $sended = false;
        }
        return $sended;
    }

    //Enviar notificación a los usuarios cuando se les asigna un plan
    public static function sendPlanNotification($accion, $usuario){
        $sended = true;
        try{
            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($usuario->email)
                ->setSubject('Lextool: '.$accion)
                ->setTextBody('Se le informa que a su usuario "'.$usuario->username.'" se le ha asignado un '.$accion)
                ->send();
        }catch (\Exception $e){
            //Yii::$app->session->setFlash('error', 'No se pudo enviar el correo de confirmación');
            $sended = false;
        }
        return $sended;
    }
}