<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Illustration;
use backend\models\IllustrationPlan;
use backend\models\IllustrationRevPlan;
use backend\models\Model;
use common\models\User;
use Yii;
use backend\models\IllustrationDocument;
use backend\models\IllustrationDocumentSearch;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\View;

/**
 * Illustration_documentController implements the CRUD actions for IllustrationDocument model.
 */
class Illustration_document_revController extends Controller
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
    public function actionIndex($id_illustration_rev_plan)
    {
        $illustration_rev_plan = IllustrationRevPlan::findOne($id_illustration_rev_plan);
        if(User::userCanIllustrationRev($illustration_rev_plan->id_project)){
            $illustration_plan = IllustrationPlan::findOne($illustration_rev_plan->id_illustration_plan);
            $searchModel = new IllustrationDocumentSearch();
            $searchModel->id_illustration_plan = $illustration_plan->id_illustration_plan;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'illustration_rev_plan' => $illustration_rev_plan,
                'project' => $illustration_plan->project,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionIllustration($id){

        $illustration = Illustration::findOne($id);
        if(User::userCanIllustrationRev($illustration->id_project)){
            return $this->renderAjax('illustration', [
                'illustration' => $illustration,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Displays a single IllustrationDocument model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if(User::userCanIllustrationRev($model->document->id_project)){
            return $this->renderAjax('view', [
                'model' => $model,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
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

        if(User::userCanIllustrationRev($model->document->id_project)){
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

                if ($model->reviewed == true)
                    $model->reviewed = false;
                else
                    $model->reviewed = true;

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
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
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
        if(User::userCanIllustrationRev($model->document->id_project)){
            if ($model->delete())
                return "Ok";
            else
                return "Error";
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
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

        throw new NotFoundHttpException('La página pedida no existe.');
    }
}
