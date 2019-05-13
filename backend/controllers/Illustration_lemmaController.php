<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Illustration;
use backend\models\IllustrationPlan;
use backend\models\Lemma;
use backend\models\Letter;
use backend\models\Model;
use Yii;
use backend\models\IllustrationLemma;
use backend\models\IllustrationLemmaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\View;

/**
 * Illustration_lemmaController implements the CRUD actions for IllustrationLemma model.
 */
class Illustration_lemmaController extends Controller
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
     * Lists all IllustrationLemma models.
     * @return mixed
     */
    public function actionIndex($id_illustration_plan)
    {
        $illustration_plan = IllustrationPlan::findOne($id_illustration_plan);
        $letters = $illustration_plan->letters;

        $searchModel = new IllustrationLemmaSearch();
        $searchModel->id_illustration_plan = $id_illustration_plan;
        //$searchModel->id_letter = $letters[0]->id_letter;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'illustration_plan' => $illustration_plan,
            'project' => $illustration_plan->project,
            'letters' => $letters,


        ]);
    }


    public function actionIllustration($id){

        $illustration = Illustration::findOne($id);
        return $this->renderAjax('illustration', [
            'illustration' => $illustration,
        ]);
    }

    /**
     * Displays a single IllustrationLemma model.
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
     * Creates a new IllustrationLemma model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_illustration_plan)
    {
        $illustration_plan = IllustrationPlan::findOne($id_illustration_plan);
        $letters = $illustration_plan->letters;

        $model = new IllustrationLemma();
        $model->id_illustration_plan = $id_illustration_plan;
        $model->id_letter = $letters[0]->id_letter;

        $project = $illustration_plan->project;
        $lemmas = [];
        if (isset($_POST['IllustrationLemma']['id_letter']))
            $lemmas = Lemma::find()->where(['id_project' => $project->id_project, 'id_letter' => $_POST['IllustrationLemma']['id_letter'], 'lemario'=> true])->orderBy('extracted_lemma')->all();
        else
            $lemmas = Lemma::find()->where(['id_project' => $project->id_project, 'id_letter' => $letters[0]->id_letter, 'lemario'=> true])->orderBy('extracted_lemma')->all();

        $modelIllustrations = [new Illustration()];

        if ($model->load(Yii::$app->request->post()) && isset($_POST['lemmas'])) {
            $lemmasList = $_POST['lemmas'];

            $modelIllustrations = Model::createMultiple(Illustration::classname(), $modelIllustrations, 'id_illustration');
            Model::loadMultiple($modelIllustrations, Yii::$app->request->post());

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $i = 0;
                foreach ($modelIllustrations as $modelIllustration) {
                    $i = $i + 1;
                    $modelIllustration->id_project = $project->id_project;
                    if (!($flag = $modelIllustration->save(false))) {
                        $transaction->rollBack();
                        break;
                    } else {
                        $file = UploadedFile::getInstance($modelIllustration, "[{$i}]url");
                        if (!empty($file)) {
                            //$address = $modelIllustration->id_illustration.' - '. $modelIllustration->name .'.'. $file->extension;
                            $address = $modelIllustration->id_illustration.' (Multiple).'. $file->extension;
                            $file->saveAs('uploads/project/illustration_lemma/' . $address);
                            $modelIllustration->url = $address;
                        } else {
                            $modelIllustration->url = "null";
                        }
                        $modelIllustration->save(false);

                        $z = 0;
                        for ($j = 0; $j < count($lemmas) && $z < count($lemmasList); $j++){
                            try{
                                if ($lemmasList[$j] == "on"){
                                    $z++;
                                    $illustrationLemma = new IllustrationLemma();
                                    $illustrationLemma->id_lemma = $lemmas[$j]->id_lemma;
                                    $illustrationLemma->id_illustration = $modelIllustration->id_illustration;
                                    $illustrationLemma->id_illustration_plan = $id_illustration_plan;
                                    $illustrationLemma->save(false);
                                }
                            } catch (\Exception $e){

                            }
                        }
                    }
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
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/illustration_lemma_create.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->render('create', [
            'model' => $model,
            'project' => $project,
            'letters' => $letters,
            'lemmas' => $lemmas,
            'modelIllustrations' => $modelIllustrations,
        ]);
    }

    public function actionLemmas($id_project, $id_letter)
    {
        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/iCheck/square/blue.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/iCheck/icheck.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_icheck.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        //$lemmas = Lemma::find()->where(['id_project' => $id_project, 'id_letter' => $id_letter, 'lemario' => false, 'agree' => true])->orderBy('extracted_lemma')->all();
        $lemmas = Lemma::find()->where(['id_project' => $id_project, 'id_letter' => $id_letter, 'lemario'=> true])->orderBy('extracted_lemma')->all();

        return $this->renderAjax('lemmas', [
            'lemmas' => $lemmas,
        ]);
    }

    /**
     * Updates an existing IllustrationLemma model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->id_letter = $model->lemma->id_letter;

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, "url");
            if (!empty($file)) {
                $modelIllustration = new Illustration();
                $modelIllustration->id_project = $model->illustrationPlan->id_project;
                $modelIllustration->save(false);

                $address = $modelIllustration->id_illustration.' (Only).'.$file->extension;
                $file->saveAs('uploads/project/illustration_lemma/' . $address);
                $modelIllustration->url = $address;
                $modelIllustration->save(false);
                $model->id_illustration = $modelIllustration->id_illustration;
            }

            if($model->save(false))
                return $model->lemma->extracted_lemma;
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
     * Deletes an existing IllustrationLemma model.
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
     * Finds the IllustrationLemma model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IllustrationLemma the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IllustrationLemma::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('No tiene permitido ejecutar esta acción.');
    }
}
