<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\IllustrationPlanDocument;
use backend\models\IllustrationPlanLetter;
use backend\models\Project;
use common\models\User;
use Yii;
use backend\models\IllustrationPlan;
use backend\models\IllustrationPlanSearch;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;

/**
 * Illustration_planController implements the CRUD actions for IllustrationPlan model.
 */
class Illustration_planController extends Controller
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
     * Lists all IllustrationPlan models.
     * @return mixed
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $searchModel = new IllustrationPlanSearch();
            $searchModel->id_project = $id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


            $this->view->registerJsFile(Yii::$app->homeUrl.'js/illustration_plan.js',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $this->findModelProject($id_project),
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Displays a single IllustrationPlan model.
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
     * Creates a new IllustrationPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new IllustrationPlan();
            $model->id_project = $id_project;

            if ($model->load(Yii::$app->request->post())) {
                $model->finished = false;

                if($model->save(false)){
                    $this->createPlanLetter($model);
                    $this->createPlanDocument($model);
                    MailController::sendPlanNotification('plan de asociación de ilustraciones', $model->user);
                    return "agregada";
                } else
                    return "Error";
            } else {

                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    private function createPlanLetter($model){
        if (is_array($model->letter))
            foreach ($model->letter as $let){
                $letter = new IllustrationPlanLetter();
                $letter->id_illustration_plan = $model->id_illustration_plan;
                $letter->id_letter = $let;
                $letter->save();
            }
    }

    private function createPlanDocument($model){
        if (is_array($model->document))
            foreach ($model->document as $doc){
                $document = new IllustrationPlanDocument();
                $document->id_illustration_plan = $model->id_illustration_plan;
                $document->id_document = $doc;
                $document->save();
            }
    }

    /**
     * Updates an existing IllustrationPlan model.
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
            if ($model->finished == false)
                $model->finished = 0;
            $model->letter = $model->letters;
            $model->document = $model->documents;

            if ($model->load(Yii::$app->request->post())) {
                if($model->save(false)){
                    $model->deleteAllPlanLetter();
                    $this->createPlanLetter($model);
                    $model->deleteAllPlanDocument();
                    $this->createPlanDocument($model);
                    return "editada";
                } else
                    return "Error";
            } else {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Deletes an existing IllustrationPlan model.
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

            $illustrationDocuments = $model->illustrationDocuments;
            $illustrationLemmas = $model->illustrationLemmas;
            $illustrationRevPlans = $model->illustrationRevPlans;

            if(count($illustrationDocuments) > 0 || count($illustrationLemmas) > 0 || count($illustrationRevPlans)){
                return "Used";
            } else {
                if ($model->delete())
                    return "Ok";
                else
                    return "Error";
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Finds the IllustrationPlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IllustrationPlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IllustrationPlan::findOne($id)) !== null) {
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
