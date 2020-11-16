<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\ElementSeparator;
use backend\models\Project;
use backend\models\Templates;
use backend\models\SubModelSeparator;
use common\models\User;
use Yii;
use backend\models\Separator;
use backend\models\SeparatorSearch;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;

/**
 * SeparatorController implements the CRUD actions for Separator model.
 */
class SeparatorController extends Controller
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
        ];
    }

    /**
     * Lists all Separator models.
     * @return mixed
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $searchModel = new SeparatorSearch();
            $searchModel->id_project = $id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


            $this->view->registerCssFile(Yii::$app->homeUrl.'css/separator.css',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl.'js/separator.js',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);


            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $this->findModelProject($id_project),
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Displays a single Separator model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            return $this->renderAjax('view', [
                'model' => $model,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Creates a new Separator model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new Separator();
            $model->id_project = $id_project;

            if ($model->load(Yii::$app->request->post())) {
                if($model->save()){
                    return $model->type;
                }
                else
                    return "Error";
            }else {


                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Updates an existing Separator model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;

        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            if ($model->load(Yii::$app->request->post())) {
                if($model->save()){
                    return $model->type;
                }
                else
                    return "Error";
            }else {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Deletes an existing Separator model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){

            $elementSeparator = ElementSeparator::findAll(['id_separator' => $id]);
            $subModelSeparator = SubModelSeparator::findAll(['id_separator' => $id]);

            if (count($elementSeparator)>0 || count($subModelSeparator) > 0)
                return "Used";
            else{
                if ($model->delete())
                    return "Ok";
                else
                    return "Error";
            }

        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Finds the Separator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Separator the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Separator::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }

    protected function findModelProject($id_project)
    {
        if (($model = Project::findOne($id_project)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }
}
