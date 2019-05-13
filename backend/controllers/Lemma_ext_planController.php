<?php

namespace backend\controllers;

use backend\models\LemmaExtPlanLetter;
use backend\models\LemmaExtPlanSemanticField;
use backend\models\LemmaExtPlanSource;
use backend\models\Project;
use backend\models\SemanticField;
use common\models\User;
use Yii;
use backend\models\LemmaExtPlan;
use backend\models\LemmaExtPlanSearch;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Lemma_ext_planController implements the CRUD actions for LemmaExtPlan model.
 */
class Lemma_ext_planController extends Controller
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
     * Lists all LemmaExtPlan models.
     * @return mixed
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $searchModel = new LemmaExtPlanSearch();
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
     * Displays a single LemmaExtPlan model.
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
     * Creates a new LemmaExtPlan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new LemmaExtPlan();
            $model->id_project = $id_project;

            if ($model->load(Yii::$app->request->post())) {
                $model->finished = false;
                if($model->save()){
                    $this->createPlanLetter($model);
                    $this->createPlanSemantic($model);
                    $this->createPlanSource($model);
                    MailController::sendPlanNotification('plan de extracción de lemas', $model->user);
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

    private function createPlanSemantic($model){
        if (is_array($model->semantic_field))
            foreach ($model->semantic_field as $semantic_field){
                $lemmaExtPlanSemanticField = new LemmaExtPlanSemanticField();
                $lemmaExtPlanSemanticField->id_lemma_ext_plan = $model->id_lemma_ext_plan;
                $lemmaExtPlanSemanticField->id_semantic_field = $semantic_field;
                $lemmaExtPlanSemanticField->save();
            }
    }

    private function createPlanLetter($model){
        if (is_array($model->letter))
            foreach ($model->letter as $let){
                $letter = new LemmaExtPlanLetter();
                $letter->id_ext_plan = $model->id_lemma_ext_plan;
                $letter->id_letter = $let;
                $letter->save();
            }
    }

    private function createPlanSource($model){
        if (is_array($model->source))
            foreach ($model->source as $sou){
                $lemmaExtPlanSource = new LemmaExtPlanSource();
                $lemmaExtPlanSource->id_ext_plan = $model->id_lemma_ext_plan;
                $lemmaExtPlanSource->id_source = $sou;
                $lemmaExtPlanSource->save();
            }
    }

    /**
     * Updates an existing LemmaExtPlan model.
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
            $model->source = $model->sources;
            $model->semantic_field = $model->semanticFields;


            if ($model->load(Yii::$app->request->post())) {
                if($model->save()){
                    $model->deleteAllPlanLetter();
                    $this->createPlanLetter($model);
                    $model->deleteAllPlanSemantic();
                    $this->createPlanSemantic($model);
                    $model->deleteAllPlanSource();
                    $this->createPlanSource($model);
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
     * Deletes an existing LemmaExtPlan model.
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

            $lemmas = $model->lemmas;
            $lemmaRevPlans = $model->lemmaRevPlans;
            if(count($lemmas) > 0 || count($lemmaRevPlans) > 0){
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
     * Finds the LemmaExtPlan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LemmaExtPlan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LemmaExtPlan::findOne($id)) !== null) {
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
