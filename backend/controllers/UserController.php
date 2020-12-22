<?php

namespace backend\controllers;

use backend\models\AuthAssignment;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','view','delete', 'habilitar'],
                'rules' => [
                    [
                        'actions' => ['index','create','view','delete', 'habilitar'],
                        'allow' => true,
                        'roles'=> ['Administrador'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = 'json';
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPassword($model->password);
            $model->enabled = false;
            $model->generateAuthKey();
            if($model->save()){
                $this->createAuthAssignment($model);
                MailController::sendActionUser('Creado',$model);
                Yii::$app->session->setFlash('success', 'El usuario "'.$model->username.'" ha sido creado');
            } else {
                Yii::$app->session->setFlash('success', 'El usuario "'.$model->username.'" no ha podido ser creado, ha ocurrdo un error');
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    private function createAuthAssignment($model){
        if (is_array($model->rol))
            foreach ($model->rol as $rol){
                $rolTemp = new AuthAssignment();
                $rolTemp->user_id = $model->id_user;
                $rolTemp->item_name = $rol;
                $rolTemp->save();
            }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->enabled == false)
            $model->enabled = 0;
        $model->rol = $model->itemNames;

        if ($model->load(Yii::$app->request->post()) ) {

            if($model->save(false)){
                $model->deleteAllRoles();
                $this->createAuthAssignment($model);
                //MailController::sendActionUser('Editado',$model);
                Yii::$app->session->setFlash('success', 'El usuario "'.$model->username.'" ha sido editado');
            } else {
                Yii::$app->session->setFlash('success', 'El usuario "'.$model->username.'" no ha podido ser editado, ha ocurrdo un error');
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($id == Yii::$app->user->identity->getId()){
            return "Guest";
        }
        $model = $this->findModel($id);
        $plans = count($model->lemmaExtPlans) + count($model->lemmaRevPlans) + count($model->docExtPlans) + count($model->docRevPlans);
        $notification = true;
        if ($plans == 0)
            if ($model->delete()){
                $notification = MailController::sendActionUser('Eliminado',$model);
                return "Ok, ".$notification;
            }
            else
                return "Error, ".$notification;
        else
            return "No, ".$notification;

    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }

    public function actionHabilitar($id)
    {
        $user = $this->findModel($id);
        $notification = true;
        if(Yii::$app->request->get()) {
            if($user->enabled==false){
                $user->enabled = true;
                $enabled = "Activo";
                $notification = MailController::sendActionUser('Habilitado',$user);
            }
            else{
                if ($id == Yii::$app->user->identity->getId()){
                    return "Guest";
                }
                $user->enabled = false;
                $enabled = "Inactivo";
                $notification = MailController::sendActionUser('Inhabilitado',$user);
            }
            $user->update(false);
        }
        return $enabled.', '. $notification;
    }

    public function importExcel(){
         $inputFile = 'uploads/excel.xlsx';

         try{

             $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
             $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
             $objPHPExcel = $objReader->load($inputFile);

             $sheet = $objPHPExcel->getSheet(0);
             $highestRow = $sheet->getHighestRow();
             $highestColumn = $sheet->getHighestColumn();

             for($row = 1; $row <= $highestRow; $row++){
                 $rowData= $sheet->rangeToArray('A'.$row . ':' . $highestColumn.$row, NULL, TRUE, FALSE);

                 if($row=1){
                     continue;
                 }

                 $user = new User();
                 $user->username = $rowData[0][1];
                 $user->password  = $rowData[0][2];
                 $user->email  = $rowData[0][3];

                 $user->save();

                 print_r($user->getErrors());
             } die('Okay');




         }catch (Exception $e){
          die ('Error');
        };

    }
}
