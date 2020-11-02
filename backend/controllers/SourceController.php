<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Project;
use backend\models\SourceLetter;
use common\models\User;
use Yii;
use backend\models\Source;
use backend\models\SourceSearch;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\View;

/**
 * SourceController implements the CRUD actions for Source model.
 */
class SourceController extends Controller
{
    public $enableCsrfValidation = false;
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
     * Lists all Source models.
     * @return mixed
     */
    public function actionIndex($id_project)
    {
//        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $searchModel = new SourceSearch();
            $searchModel->id_project = $id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $this->findModelProject($id_project),

            ]);
//        } else
//            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Displays a single Source model.
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
     * Creates a new Source model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
//        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new Source();
            $model->id_project = $id_project;
            $model->scenario = "create";

            if ($model->load(Yii::$app->request->post())) {

                $model->save(false);
                $file = UploadedFile::getInstance($model, "url");
                if (!empty($file)) {
                    $address = $model->id_source.' - '. $model->name .' (Fuente).'.$file->extension;
                    $file->saveAs('uploads/project/source/' . $address);
                    $model->url = $address;
                } else {
                    $model->url = "null";
                }

                if($model->save(false)){
                    $this->createSourceLetter($model);
                    return $model->name;
                }
                else
                    return "Error";
            } else {
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }
//        } else
//            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    private function createSourceLetter($model){

        if (is_array($model->letter))
            foreach ($model->letter as $let){
                $letter = new SourceLetter();
                $letter->id_source = $model->id_source;
                $letter->id_letter = $let;
                $letter->save();
            }

    }

    /**
     * Updates an existing Source model.
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
            if ($model->editable == false)
                $model->editable = 0;
            $model->letter = $model->letters;

            if ($model->load(Yii::$app->request->post())) {

                $file = UploadedFile::getInstance($model, "url");
                if (!empty($file)) {
                    $address = $model->id_source.' - '. $model->name .' (Fuente).'.$file->extension;
                    $file->saveAs('uploads/project/source/' . $address);
                    $model->url = $address;
                } else
                    $model->url = $model->oldAttributes['url'];

                if($model->save(false)){
                    $model->deleteAllSourceLetter();
                    $this->createSourceLetter($model);
                    return $model->name;
                }
                else
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
     * Deletes an existing Source model.
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
            $lemmaExtPlans = $model->extPlans;
            $docExtPlans = $model->docExtPlans;
            if (count($lemmaExtPlans) > 0 || count($docExtPlans) > 0){
                return "Used";
            } else {
                try{
                    if ($model->url != "null")
                        unlink('uploads/project/source/'.$model->url);
                }catch (\Exception $exception){

                }
                if ($model->delete())
                    return "Ok";
                else
                    return "Error";
            }

        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Finds the Source model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Source the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Source::findOne($id)) !== null) {
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
