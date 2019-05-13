<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Model;
use backend\models\Source;
use backend\models\SourceLetter;
use backend\models\SourceSearch;
use backend\models\UserProject;
use backend\models\UserProjectSearch;
use Yii;
use backend\models\Project;
use backend\models\ProjectSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
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
                'only' => ['index','create','update','view','delete'],
                'rules' => [
                    [
                        'actions' => ['index','create','update','delete'],
                        'allow' => true,
                        'roles'=> ['Jefe de Proyecto'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/project_delete.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDetail($id)
    {
        $model = $this->findModel($id);
        $searchModel = new UserProjectSearch();
        $searchModel->id_project = $model->id_project;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModelSource = new SourceSearch();
        $searchModelSource->id_project = $model->id_project;
        $dataProviderSource = $searchModelSource->search(Yii::$app->request->queryParams);
        return $this->render('detail', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelSource' => $searchModelSource,
            'dataProviderSource' => $dataProviderSource,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $searchModel = new UserProjectSearch();
        $searchModel->id_project = $model->id_project;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModelSource = new SourceSearch();
        $searchModelSource->id_project = $model->id_project;
        $dataProviderSource = $searchModelSource->search(Yii::$app->request->queryParams);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/project_delete.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelSource' => $searchModelSource,
            'dataProviderSource' => $dataProviderSource,
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
        $modelTeams = [new UserProject()];
        $modelSources = [new Source()];
        $model->scenario = "create";

        if ($model->load(Yii::$app->request->post())) {

            $model->save(false);

            $image = UploadedFile::getInstance($model, "image");
            if (!empty($image)) {
                $address = $model->id_project.' - '. $model->name . '.' . $image->extension;
                $image->saveAs('uploads/project/image/' . $address);
                $model->image = $address;
            } else {
                $model->image = "default.jpg";
            }

            $file = UploadedFile::getInstance($model, "plant_file");
            if (!empty($file)) {
                $address = $model->id_project.' - '. $model->name .' (Planta).'.$file->extension;
                $file->saveAs('uploads/project/plant/' . $address);
                $model->plant_file = $address;
            } else {
                $model->plant_file = "default.jpg";
            }

            $model->save(false);
            $modelUserProject = new UserProject();
            $modelUserProject->id_user = $model->id_user;
            $modelUserProject->id_project = $model->id_project;
            $modelUserProject->role = "Jefe de Proyecto";
            $modelUserProject->save();
            $this->createTeams($model,$modelTeams);
            $this->createSources($model,$modelSources);

            return $this->redirect(['view', 'id' => $model->id_project]);
        }

        $this->view->registerCssFile(Yii::$app->homeUrl.'css/project.css',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl.'js/project.js',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);

        return $this->render('create', [
            'model' => $model,
            'modelTeams' => $modelTeams,
            'modelSources' => $modelSources,

        ]);
    }

    private function createTeams($model, $modelTeams)
    {
        $modelTeams = Model::createMultiple(UserProject::classname(), $modelTeams, 'id_user_project');
        Model::loadMultiple($modelTeams, Yii::$app->request->post());

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($flag = $model->save(false)) {
                foreach ($modelTeams as $modelTeam) {
                    $modelTeam->id_project = $model->id_project;
                    if ($modelTeam->validate())
                    if (!($flag = $modelTeam->save(false))) {
                        $transaction->rollBack();
                        break;
                    }
                }
            }
            if ($flag) {
                $transaction->commit();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
    }

    private function createSources($model, $modelSources)
    {
        $modelSources = Model::createMultiple(Source::classname(), $modelSources, 'id_source');
        Model::loadMultiple($modelSources, Yii::$app->request->post());

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($flag = $model->save(false)) {
                $i = -1;
                foreach ($modelSources as $modelSource) {
                    $i = $i + 1;
                    $modelSource->id_project = $model->id_project;
                    if (!($flag = $modelSource->save(false))) {
                        $transaction->rollBack();
                        break;
                    } else{
                        $this->createSourceLetter($modelSource);
                        $file = UploadedFile::getInstance($modelSource, "[{$i}]url");
                        if (!empty($file)) {
                            $address = $modelSource->id_source.' - '. $modelSource->name .' (Fuente).'.$file->extension;
                            $file->saveAs('uploads/project/source/' . $address);
                            $modelSource->url = $address;
                        } else {
                            $modelSource->url = "null";
                        }
                        $modelSource->save();
                    }
                }
            }
            if ($flag) {
                $transaction->commit();
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
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
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldBoss = UserProject::findOne(['id_project' => $model->id_project, 'role' => 'Jefe de Proyecto']);
        $model->id_user = $oldBoss->id_user;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->id_user != $oldBoss->id_user){
                $boss = new UserProject();
                $boss->id_user = $model->id_user;
                $boss->id_project = $model->id_project;
                $boss->role = "Jefe de Proyecto";
                $boss->save();
                $oldBoss->delete();
            }

            $image = UploadedFile::getInstance($model, "image");
            if (!empty($image)) {
                $address = $model->id_project.' - '. $model->name . '.' . $image->extension;
                $image->saveAs('uploads/project/image/' . $address);
                $model->image = $address;
            } else if ($model->oldAttributes['image'] != '') {
                $model->image = $model->oldAttributes['image'];
            } else if ($model->image == 'borrado') {
                //ToDo: Hacer borrar archivo
            }

            $file = UploadedFile::getInstance($model, "plant_file");
            if (!empty($file)) {
                $address = $model->id_project.' - '. $model->name .' (Planta).'.$file->extension;
                $file->saveAs('uploads/project/plant/' . $address);
                $model->plant_file = $address;
            } else
                $model->plant_file = $model->oldAttributes['plant_file'];

            $model->save(false);

            return $this->redirect(['view', 'id' => $model->id_project]);
        }
        $this->view->registerCssFile(Yii::$app->homeUrl.'css/project.css',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl.'js/project.js',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $sources = $model->sources;
        $lemma_ext_plans = $model->lemmaExtPlans;
        $lemma_rev_plans = $model->lemmaRevPlans;
        $doc_ext_plans = $model->docExtPlans;
        $doc_rev_plans = $model->docRevPlans;
        $doc_make_plans = $model->docMakePlans;
        $redaction_plans = $model->redactionPlans;
        $revision_plans = $model->revisionPlans;
        $illustration_plan = $model->illustrationPlans;
        $illustration_rev_plan = $model->illustrationRevPlans;
        $lemmas = $model->lemmas;
        $docs = $model->complementaryDocs;

        if(count($sources) > 0 || count($lemma_ext_plans)  > 0 || count($lemma_rev_plans)  > 0 || count($doc_ext_plans) > 0
            || count($doc_rev_plans) > 0 || count($doc_make_plans)  > 0 || count($redaction_plans)  > 0 ||count($revision_plans) > 0
            || count($illustration_plan)  > 0 || count($illustration_rev_plan)  > 0 || count($lemmas)  > 0 || count($docs)  > 0 ){
            return "Used";
        } else {
            if ($model->delete()){
                try{
                    if ($model->image != "default.jpg" && $model->image != "")
                        unlink('uploads/project/image/'.$model->image);
                    if ($model->plant_file != "null")
                        unlink('uploads/project/plant/'.$model->plant_file);
                }catch (\Exception $exception){

                }
                return "Ok";
            }

            else
                return "Error";
        }
    }

    public function actionDelete_view($id)
    {
        $model = $this->findModel($id);
        $sources = $model->sources;
        $lemma_ext_plans = $model->lemmaExtPlans;
        $lemma_rev_plans = $model->lemmaRevPlans;
        $doc_ext_plans = $model->docExtPlans;
        $doc_rev_plans = $model->docRevPlans;
        $doc_make_plans = $model->docMakePlans;
        $redaction_plans = $model->redactionPlans;
        $revision_plans = $model->revisionPlans;
        $illustration_plan = $model->illustrationPlans;
        $illustration_rev_plan = $model->illustrationRevPlans;
        $lemmas = $model->lemmas;
        $docs = $model->complementaryDocs;

        if(count($sources) > 0 || count($lemma_ext_plans)  > 0 || count($lemma_rev_plans)  > 0 || count($doc_ext_plans) > 0
            || count($doc_rev_plans) > 0 || count($doc_make_plans)  > 0 || count($redaction_plans)  > 0 ||count($revision_plans) > 0
            || count($illustration_plan)  > 0 || count($illustration_rev_plan)  > 0 || count($lemmas)  > 0 || count($docs)  > 0 ){
            return "Used";
        } else {
            if ($model->delete()){
                try{
                    if ($model->image != "default.jpg" && $model->image != "")
                        unlink('uploads/project/image/'.$model->image);
                    if ($model->plant_file != "null")
                        unlink('uploads/project/plant/'.$model->plant_file);
                }catch (\Exception $exception){

                }
                return $this->redirect(['index']);
            }

            else
                return "Error";
        }
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDictionary($id)
    {
        $this->view->registerCssFile(Yii::$app->homeUrl.'css/alvaro.css',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl.'js/dictionary.js',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);

        $model = $this->findModel($id);

        return $this->render('dictionary', [
            'model' => $model,
        ]);
    }

    public function actionDelete_image()
    {
        if(isset($_POST['key']) && $_POST['key'] != ""){

            $model = $this->findModel($_POST['key']);
            if ($model->image != "default.jpg" && $model->image != "")
                unlink('uploads/project/image/'.$model->image);
            $model->image = "default.jpg";

            $model->save(false);
        }
        return "{}";
    }
}
