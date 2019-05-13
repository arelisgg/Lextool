<?php
namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\PasswordResetRequestForm;
use backend\models\Project;
use backend\models\ResetPasswordForm;
use common\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'myprofile', 'login', 'control', 'request-password-reset', 'reset-password', 'singup'],
                'rules' => [
                    [
                        'actions' => ['login', 'request-password-reset', 'reset-password', 'singup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'myprofile', 'control'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $projects = Project::find()->all();

        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        return $this->render('index',
            ['projects' => $projects]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

           $this->view->registerJsFile(Yii::$app->homeUrl.'js/login.js',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSingup()
    {
        $model = new User();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            $model->enabled = false;
            $model->generateAuthKey();
            $model->save(false);

            MailController::sendRegisterUser($model, 'creado');
            MailController::sendToAdminRegisterUser($model);

            return $this->redirect(['login']);
        } else {

            return $this->render('singup', [
                'model' => $model,
            ]);
        }
    }

    public function actionMyprofile($id)
    {
        $model = User::findOne($id);
        $model->scenario = 'update';
        $model->password = "";

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            if($model->save(false)){
                Yii::$app->session->setFlash('success', 'Su perfil ha sido actualizado');
                return $this->redirect(['myprofile', 'id' => $model->id_user]);
            } else{
                Yii::$app->session->setFlash('error', 'Su perfil no ha podido ser actualizado, ha ocurrido un error');
                return $this->redirect(['myprofile', 'id' => $model->id_user]);
            }
        } else {
            return $this->render('myprofile', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Verifique su correo para nuevas instrucciones.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Disculpe, no pudimos resetear su contraseña para el email insertado.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->resetPassword();
            Yii::$app->session->setFlash('success', 'Su nueva contraseña ha sido salvada.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionControl($id_project){
        $control = User::userCanProjectAndRol($id_project, "Jefe de Proyecto").','.
            User::userCanExtractionLemma($id_project).','.
            User::userCanRevitionLemma($id_project).','.
            User::userCanExtractionDocument($id_project).','.
            User::userCanRevitionDocument($id_project).','.
            User::userCanConfectionDocument($id_project).','.
            User::userCanRedaction($id_project).','.
            User::userCanRevition($id_project).','.
            User::userCanIllustration($id_project).','.
            User::userCanIllustrationRev($id_project);
        return $control;
    }

}
