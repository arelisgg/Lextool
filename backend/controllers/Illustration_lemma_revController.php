<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Illustration;
use backend\models\IllustrationPlan;
use backend\models\IllustrationRevPlan;
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
class Illustration_lemma_revController extends Controller
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
    public function actionIndex($id_illustration_rev_plan)
    {

        $illustration_rev_plan = IllustrationRevPlan::findOne($id_illustration_rev_plan);
        $illustration_plan = IllustrationPlan::findOne($illustration_rev_plan->id_illustration_plan);
        $letters = $illustration_plan->letters;

        $searchModel = new IllustrationLemmaSearch();
        $searchModel->id_illustration_plan = $illustration_rev_plan->id_illustration_plan;
        //$searchModel->id_letter = $letters[0]->id_letter;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'illustration_rev_plan' => $illustration_rev_plan,
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
            if ($model->reviewed == true)
                $model->reviewed = false;
            else
                $model->reviewed = true;

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
