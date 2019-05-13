<?php

namespace backend\controllers;

use backend\models\Project;
use backend\models\RedactionPlanElement;
use backend\models\RedactionPlanLetter;
use backend\models\RedactionPlanSubmodel;
use common\models\User;
use Yii;
use backend\models\RedactionPlan;
use backend\models\RedactionPlanSearch;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Redaction_planController implements the CRUD actions for RedactionPlan model.
 */
class Redaction_planController extends Controller
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
     * Lists all RedactionPlan models.
     * @return mixed
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $searchModel = new RedactionPlanSearch();
            $searchModel->id_project = $id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $this->findModelProject($id_project),
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Displays a single RedactionPlan model.
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
     * Creates a new RedactionPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new RedactionPlan();
            $model->id_project = $id_project;

            if ($model->load(Yii::$app->request->post())) {
                $model->finished = false;

                if($model->save()){
                    $this->createPlanLetter($model);
                    $this->createPlanSubmodel($model);
                    MailController::sendPlanNotification('plan de redacción de lemas', $model->user);
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
                $letter = new RedactionPlanLetter();
                $letter->id_redaction_plan = $model->id_redaction_plan;
                $letter->id_letter = $let;
                $letter->save();
            }
    }

    private function createPlanSubmodel($model){
        if (is_array($model->submodel))
            foreach ($model->submodel as $item){
                $submodel = new RedactionPlanSubmodel();
                $submodel->id_redaction_plan = $model->id_redaction_plan;
                $submodel->id_sub_model = $item;
                $submodel->save();
            }
    }

    /**
     * Updates an existing RedactionPlan model.
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
            $model->submodel = $model->submodels;

            if ($model->load(Yii::$app->request->post())) {
                if($model->save()){
                    $model->deleteAllPlanLetter();
                    $this->createPlanLetter($model);
                    $model->deleteAllPlanSubmodel();
                    $this->createPlanSubmodel($model);
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
     * Deletes an existing RedactionPlan model.
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
     * Finds the RedactionPlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RedactionPlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RedactionPlan::findOne($id)) !== null) {
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
