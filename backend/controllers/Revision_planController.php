<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Project;
use backend\models\RedactionPlanLetter;
use backend\models\RedactionPlanSubmodel;
use backend\models\RevisionPlanCriteria;
use backend\models\RevisionPlanLetter;
use backend\models\RevisionPlanSubmodel;
use common\models\User;
use Yii;
use backend\models\RevisionPlan;
use backend\models\RevisionPlanSearch;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;

/**
 * Revision_planController implements the CRUD actions for RevisionPlan model.
 */
class Revision_planController extends Controller
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
     * Lists all RevisionPlan models.
     * @return mixed
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $searchModel = new RevisionPlanSearch();
            $searchModel->id_project = $id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/revision_plan.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $this->findModelProject($id_project),
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Displays a single RevisionPlan model.
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
     * Creates a new RevisionPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new RevisionPlan();
            $model->id_project = $id_project;

            if ($model->load(Yii::$app->request->post())) {
                $model->finished = false;
                if($model->save(false)){
                    $this->createPlanLetter($model);
                    $this->createPlanSubmodel($model);
                    $this->createPlanCriterias($model);
                    MailController::sendPlanNotification('plan de revisión de lemas extraidos', $model->user);
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
                $letter = new RevisionPlanLetter();
                $letter->id_revision_plan = $model->id_revision_plan;
                $letter->id_letter = $let;
                $letter->save();
            }
    }

    private function createPlanSubmodel($model){
        if (is_array($model->submodel))
            foreach ($model->submodel as $item){
                $submodel = new RevisionPlanSubmodel();
                $submodel->id_revision_plan = $model->id_revision_plan;
                $submodel->id_sub_model = $item;
                $submodel->save();
            }
    }

    private function createPlanCriterias($model){
        if (is_array($model->criterias))
            foreach ($model->criterias as $item){
                $submodel = new RevisionPlanCriteria();
                $submodel->id_revision_plan = $model->id_revision_plan;
                $submodel->id_review_criteria = $item;
                $submodel->save();
            }
    }

    /**
     * Updates an existing RevisionPlan model.
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
            if ($model->edition === false)
                $model->edition = 0;
            if ($model->finished === false)
                $model->finished = 0;
            $model->letter = $model->letters;
            $model->submodel = $model->subModels;
            $model->criterias = $model->reviewCriterias;

            if ($model->load(Yii::$app->request->post())) {
                if ($model->type === "Léxica")
                    $model->edition = "";
                if($model->save(false)){
                    $model->deleteAllPlanLetter();
                    $this->createPlanLetter($model);

                    $model->deleteAllPlanSubmodel();
                    $this->createPlanSubmodel($model);

                    $model->deleteAllPlanCriteria();
                    $this->createPlanCriterias($model);
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
     * Deletes an existing RevisionPlan model.
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
            if ($model->delete())
                return "Ok";
            else
                return "Error";
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Finds the RevisionPlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RevisionPlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RevisionPlan::findOne($id)) !== null) {
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
