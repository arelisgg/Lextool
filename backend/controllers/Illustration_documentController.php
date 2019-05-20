<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Illustration;
use backend\models\IllustrationPlan;
use backend\models\Model;
use Yii;
use backend\models\IllustrationDocument;
use backend\models\IllustrationDocumentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\View;

/**
 * Illustration_documentController implements the CRUD actions for IllustrationDocument model.
 */
class Illustration_documentController extends Controller
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
     * Lists all IllustrationDocument models.
     * @return mixed
     */
    public function actionIndex($id_illustration_plan)
    {
        $searchModel = new IllustrationDocumentSearch();
        $searchModel->id_illustration_plan = $id_illustration_plan;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $illustration_plan = IllustrationPlan::findOne($id_illustration_plan);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'illustration_plan' => $illustration_plan,
            'project' => $illustration_plan->project,
        ]);
    }

    public function actionIllustration($id){

        $illustration = Illustration::findOne($id);
        return $this->renderAjax('illustration', [
            'illustration' => $illustration,
        ]);
    }

    /**
     * Displays a single IllustrationDocument model.
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
     * Creates a new IllustrationDocument model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_illustration_plan)
    {

        $illustration_plan = IllustrationPlan::findOne($id_illustration_plan);
        $documents = $illustration_plan->documents;

        $model = new IllustrationDocument();
        $model->id_illustration_plan = $id_illustration_plan;
        $project = $illustration_plan->project;

        $modelIllustrations = [new Illustration()];

        if ($model->load(Yii::$app->request->post()) && isset($_POST['documents'])) {
            $documentsList = $_POST['documents'];

            $modelIllustrations = Model::createMultiple(Illustration::classname(), $modelIllustrations, 'id_illustration');
            Model::loadMultiple($modelIllustrations, Yii::$app->request->post());
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $ilus = Yii::$app->request->post("Illustration");
                $first = array_shift($ilus);
                $i = array_search($first, Yii::$app->request->post("Illustration"));
                foreach ($modelIllustrations as $modelIllustration) {
                    $modelIllustration->id_project = $project->id_project;
                    if (!($flag = $modelIllustration->save(false))) {
                        $transaction->rollBack();
                        break;
                    } else {
                        $file = UploadedFile::getInstance($modelIllustration, "[{$i}]url");
                        if (!empty($file)) {
                            //$address = $modelIllustration->id_illustration.' - '. $modelIllustration->name .'.'. $file->extension;
                            $address = $modelIllustration->id_illustration.' (Multiple).'. $file->extension;
                            $file->saveAs('uploads/project/illustration_document/' . $address);
                            $modelIllustration->url = $address;
                        } else {
                            $modelIllustration->url = "null.jpg";
                        }
                        $modelIllustration->save(false);
                        for ($j = 0; $j < count($documents); $j++){
                            try{
                                if ($documentsList[$j] == "on"){
                                    $illustrationDocument = new IllustrationDocument();
                                    $illustrationDocument->id_document = $documents[$j]->id_complementary_doc;
                                    $illustrationDocument->id_illustration = $modelIllustration->id_illustration;
                                    $illustrationDocument->id_illustration_plan = $id_illustration_plan;
                                    $illustrationDocument->save(false);
                                }
                            } catch (\Exception $e){

                            }
                        }
                    }
                    $i++;
                }
                if ($flag) {
                    $transaction->commit();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
            }

            return $this->redirect(['index', 'id_illustration_plan' => $id_illustration_plan]);
        }

        //iCheck
        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/iCheck/square/blue.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/iCheck/icheck.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_icheck.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerCssFile(Yii::$app->homeUrl.'css/illustration_lemma_create.css',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/illustration_document_create.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


        return $this->render('create', [
            'model' => $model,
            'project' => $project,
            'documents' => $documents,
            'modelIllustrations' => $modelIllustrations,
        ]);
    }

    /**
     * Updates an existing IllustrationDocument model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, "url");
            if (!empty($file)) {
                $modelIllustration = new Illustration();
                $modelIllustration->id_project = $model->illustrationPlan->id_project;
                $modelIllustration->save(false);

                $address = $modelIllustration->id_illustration.' (Only).'.$file->extension;
                $file->saveAs('uploads/project/illustration_document/' . $address);
                $modelIllustration->url = $address;
                $modelIllustration->save(false);
                $model->id_illustration = $modelIllustration->id_illustration;
            }

            if($model->save(false))
                return $model->document->docType->name;
            else
                return "Error";
        } else {
            $this->view->registerCssFile(Yii::$app->homeUrl.'css/illustration_lemma_update.css',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing IllustrationDocument model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete())
            return "Ok";
        else
            return "Error";
    }

    /**
     * Finds the IllustrationDocument model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IllustrationDocument the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IllustrationDocument::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
