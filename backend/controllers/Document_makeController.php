<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\ComplementaryDoc;
use backend\models\ComplementaryDocSearch;
use backend\models\DocMakePlan;
use backend\models\Document;
use backend\models\DocumentSearch;
use backend\models\Project;
use common\models\User;
use Yii;

use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\web\View;
use kartik\mpdf\Pdf;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

/**
 * Document_importController implements the CRUD actions for ComplementaryDoc model.
 */
class Document_makeController extends Controller
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

    public function actionPlans($id_project)
    {
        if (User::userCanConfectionDocument($id_project)) {
            $project = Project::findOne($id_project);

            $user = Yii::$app->user;

            $plans = DocMakePlan::find()->where(['id_user' => $user->id , 'id_project' => $id_project, 'finished' => false])->all();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('plans', [
                'plans' => $plans,
                'project' => $project,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    public function actionIndex($id_doc_make_plan)
    {
        $plan = $this->findModelDocMakePlan($id_doc_make_plan);
        if (User::userCanConfectionDocument($plan->id_project)) {
            $project = $plan->project;
            $docTypes = $plan->docTypes;

            $searchModelDocument = new DocumentSearch();
            $searchModelDocument->id_project = $plan->id_project;
            $searchModelDocument->docTypes = $docTypes;
            $dataProviderDocument = $searchModelDocument->search(Yii::$app->request->queryParams);

            $searchModel = new ComplementaryDocSearch();
            $searchModel->id_project = $plan->id_project;
            $searchModel->docTypes = $docTypes;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModelDocument' => $searchModelDocument,
                'dataProviderDocument' => $dataProviderDocument,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'plan' => $plan,
                'project' => $project
            ]);
        }  else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionExport($id_document)
    {
        $document = $this->findModelDocument($id_document);
        if (User::userCanConfectionDocument($document->id_project)) {
            $document_images = $document->docImages;
            $source = $document->docExtPlan->source;

            $editable = $source->editable;

            $extension = explode('.', $source->url);

            foreach ($extension as $ext){
                if ($ext == 'pdf' && $editable) {
                    $pdf = new Pdf([
                        'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
                        'destination' => Pdf::DEST_DOWNLOAD,
                        'content' => $this->renderPartial('pdf', ['model' => $document]),
                        'options' => [
                            // any mpdf options you wish to set
                        ],
                        'methods' => [
                            'SetTitle' => $document->docType->name,
                            'SetSubject' => 'Proyecto: '.$document->project->name,
                            'SetHeader' => ['Lextool 1.0 || Generado: ' . date("r")],
                            'SetFooter' => ['|Página {PAGENO}|'],
                            'SetAuthor' => 'Lextool 1.0',
                            'SetCreator' => 'Lextool 1.0',
                        ]
                    ]);
                    return $pdf->render();
                }else if ($ext == 'pdf' && !$editable || $ext == 'jpg' || $ext == 'png' || $ext == 'jpeg') {
                    try {
                        $mpdf = new Mpdf();
                        $html= $this->renderPartial('image', [ 'document_images' => $document_images, 'doc_type' => $document->docType->name ]);
                        $mpdf->WriteHTML($html);
                        $mpdf->Output($document->docType->name.".pdf",'D');
                        $mpdf->debug = true;
                        return $this->redirect(['index', [
                            'id_project' => $document->id_project
                        ]]);
                    } catch (MpdfException $e) {
                    }
                }
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionView($id, $id_doc_make_plan)
    {
        $model = $this->findModel($id);
        if (User::userCanConfectionDocument($model->id_project)) {
            return $this->render('view', [
                'model' => $model,
                'project' => $this->findModelProject($model->id_project),
                'id_doc_make_plan' => $id_doc_make_plan,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionCreate($id_doc_make_plan)
    {
        $model = new ComplementaryDoc();
        $plan = $this->findModelDocMakePlan($id_doc_make_plan);
        if (User::userCanConfectionDocument($plan->id_project)) {
            $project = $plan->project;
            $docTypes = $plan->docTypes;
            $model->scenario = "create";

            if ($model->load(Yii::$app->request->post())) {
                $model->id_project = $project->id_project;
                $model->save(false);
                $file = UploadedFile::getInstance($model, "url");
                if (!empty($file)) {
                    $address = $model->id_complementary_doc.' - '. $model->name .' (Documento Complementario).'.$file->extension;
                    $file->saveAs('uploads/project/complementary_doc/' . $address);
                    $model->url = $address;
                } else {
                    $model->url = "null";
                }
                $model->save(false);
                return $this->redirect(['index','id_doc_make_plan' => $plan->id_doc_make_plan]);
            }

            return $this->render('create', [
                'model' => $model,
                'project' => $project,
                'docTypes' => $docTypes,
                'plan' => $plan,

            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionUpdate($id, $id_doc_make_plan)
    {
        $model = $this->findModel($id);
        $plan = $this->findModelDocMakePlan($id_doc_make_plan);
        if (User::userCanConfectionDocument($plan->id_project)) {
            $project = $plan->project;
            $docTypes = $plan->docTypes;

            if ($model->load(Yii::$app->request->post())) {

                $file = UploadedFile::getInstance($model, "url");
                if (!empty($file)) {
                    $address = $model->id_complementary_doc.' - '. $model->name .' (Documento Complementario).'.$file->extension;
                    $file->saveAs('uploads/project/complementary_doc/' . $address);
                    $model->url = $address;
                } else
                    $model->url = $model->oldAttributes['url'];

                $model->save(false);

                return $this->redirect(['document_make/index','id_doc_make_plan' => $id_doc_make_plan]);
            }

            return $this->render('update', [
                'model' => $model,
                'project' => $project,
                'docTypes' => $docTypes,
                'plan' => $plan,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionDelete($id, $id_doc_make_plan)
    {
        $model = $this->findModel($id);
        if (User::userCanConfectionDocument($model->id_project)) {
            try{

                if ($model->url != "null")
                    unlink('uploads/project/complementary_doc/'.$model->url);
            }catch (\Exception $exception){

            }
            $model->delete();
            return $this->redirect(['document_make/index','id_doc_make_plan' => $id_doc_make_plan]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionFinish($id_doc_make_plan){
        $plan = $this->findModelDocMakePlan($id_doc_make_plan);
        $id_project = $plan->id_project;
        if (User::userCanConfectionDocument($id_project)) {
            $plan->finished = true;
            $plan->save(false);

            $project = Project::findOne($id_project);
            $user = Yii::$app->user;
            $plans = DocMakePlan::find()->where(['id_user' => $user->id , 'id_project' => $id_project, 'finished' => false])->all();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('plans', [
                'plans' => $plans,
                'project' => $project,
            ]);

//            return $this->redirect(['plans', 'id_project' => $plan->id_project]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }


    protected function findModelDocMakePlan($id_doc_make_plan)
    {
        if (($model = DocMakePlan::findOne($id_doc_make_plan)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelDocument($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelProject($id_project)
    {
        if (($model = Project::findOne($id_project)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModel($id)
    {
        if (($model = ComplementaryDoc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
