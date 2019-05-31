<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\DocExtPlan;
use backend\models\DocImage;
use backend\models\Project;
use backend\models\Source;
use common\models\User;
use Yii;
use backend\models\Document;
use backend\models\DocumentSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;

/**
 * Document_ext_taskController implements the CRUD actions for Document model.
 */
class Document_ext_taskController extends Controller
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
                    'image-delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Document models.
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionIndex($id_ext_plan)
    {

        $ext_plan = DocExtPlan::findOne($id_ext_plan);

        if (User::userCanExtractionDocument($ext_plan->id_project)) {
            $searchModel = new DocumentSearch();
            $searchModel->id_doc_type = $ext_plan->id_doc_type;
            $searchModel->id_doc_ext_plan = $ext_plan->id_doc_ext_plan;
            $searchModel->id_project = $ext_plan->id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $project = Project::findOne(['id_project' => $ext_plan->id_project]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/loadSourceDocument.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('index', [
                'ext_plan' => $ext_plan,
                'searchModel' => $searchModel,
                'source' => $ext_plan->source,
                'dataProvider' => $dataProvider,
                'project' => $project
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Displays a single Document model.
     * @param integer $id
     * @param $id_ext_plan
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionView($id,$id_ext_plan)
    {
        $model = Document::findOne($id);

        $ext_plan = DocExtPlan::findOne(['id_doc_ext_plan' => $id_ext_plan]);

        if (User::userCanExtractionDocument($ext_plan->id_project)) {
            $project = Project::findOne(['id_project' => $ext_plan->id_project]);

            //Visor De Imagenes
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/lightgallery.js/dist/css/lightgallery.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/lightgallery.js/dist/js/lightgallery.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/lightgallery.js/dist/js/lg-pager.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/lightgallery.js/dist/js/lg-autoplay.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/lightgallery.js/dist/js/lg-fullscreen.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/lightgallery.js/dist/js/lg-zoom.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/lightgallery.js/dist/js/lg-hash.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/lightgallery.js/dist/js/lg-share.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_viewer.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('view', [
                'model' => $model,
                'ext_plan' => $ext_plan,
                'project' => $project
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException
     * @throws NotAcceptableHttpException
     */
    public function actionCreate()
    {
        $model = new Document();

        if ($model->load(Yii::$app->request->post())){
            $id_ext_plan = $model->id_doc_ext_plan;
            $id_project = $model->id_project;
        } else {
            $id_source = Yii::$app->request->post('id_source');
            $source = Source::findOne(['id_source' => $id_source]);

            $id_ext_plan = Yii::$app->request->post('id_ext_plan');
            $id_project = Yii::$app->request->post('id_project');
        }

        $project = Project::findOne(['id_project' => $id_project]);
        $ext_plan = DocExtPlan::findOne($id_ext_plan);

        if (User::userCanExtractionDocument($project->id_project)) {

            if ($model->load(Yii::$app->request->post())) {
                $id_document = Yii::$app->request->post('id_document');

                if ($id_document != null && $id_document != "") {
                    $model = $this->findModel($id_document);
                }

                $model->reviewed = false;
                $model->save();

                $source = $model->docExtPlan->source;
                $extension = explode('.', $source->url);
                $url = Yii::$app->request->post('image_url');


                foreach ($extension as $ext) {
                    if ($ext == "pdf" && !$source->editable) {

                        if (Yii::$app->request->post('x') != "" &&
                            Yii::$app->request->post('y') != "" &&
                            Yii::$app->request->post('h') != "" &&
                            Yii::$app->request->post('w') != "") {

                            $w = Yii::$app->request->post('w');
                            $h = Yii::$app->request->post('h');
                            $y = Yii::$app->request->post('y');
                            $x = Yii::$app->request->post('x');

                            $this->createPdfImage($w, $h, $y, $x, $url, $model);

                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            return $this->render('create', [
                                'model' => $model,
                                'source' => $model->docExtPlan->source,
                                'ext_plan' => $model->docExtPlan,
                                'project' => $model->project,
                                'extension' => $extension
                            ]);
                        }

                    } elseif ($ext == "jpg" ||
                        $extension == "jpeg" ||
                        $extension == "png") {

                        if (Yii::$app->request->post('x') != "" &&
                            Yii::$app->request->post('y') != "" &&
                            Yii::$app->request->post('h') != "" &&
                            Yii::$app->request->post('w') != "") {

                            $w = Yii::$app->request->post('w');
                            $h = Yii::$app->request->post('h');
                            $y = Yii::$app->request->post('y');
                            $x = Yii::$app->request->post('x');

                            $this->createImage($w, $h, $y, $x, $model);

                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            return $this->render('create', [
                                'model' => $model,
                                'source' => $model->docExtPlan->source,
                                'ext_plan' => $model->docExtPlan,
                                'project' => $model->project,
                                'extension' => $extension
                            ]);
                        }
                    } elseif ($ext == "pdf" && $source->editable) {
                        return $this->redirect(['index', 'id_ext_plan' => $model->id_doc_ext_plan]);
                    }
                }
            }

            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $extension = explode('.', $source->url);

            return $this->render('create', [
                'model' => $model,
                'source' => $source,
                'ext_plan' => $ext_plan,
                'project' => $project,
                'extension' => $extension
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    private function createImage($w,$h,$y,$x,$model) {

        $doc_image = new DocImage();

        $jpeg_quality = 100;

        $doc_image->save();

        $doc_image->name = $doc_image->id_doc_image.'-'. $model->docExtPlan->source->name . '.jpg';
        $doc_image->url = 'uploads/project/comp_doc_img/';
        $doc_image->id_document = $model->id_document;

        $doc_image->save();

        $src = '../web/uploads/project/source/'.$model->docExtPlan->source->url;

        $img_r = imagecreatefromjpeg($src);

        $dst_r = ImageCreateTrueColor($w, $h);

        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y,
            $w, $h, $w, $h);

        copy($src, '../web/uploads/project/comp_doc_img/' . $doc_image->name);

        header('Content-type: image/jpeg');
        imagejpeg($dst_r, '../web/uploads/project/comp_doc_img/' . $doc_image->name, $jpeg_quality);
    }

    private function createPdfImage($w,$h,$y,$x,$url,$model)
    {
        $doc_image = new DocImage();

        $jpeg_quality = 100;

        $doc_image->save();

        $doc_image->name = $doc_image->id_doc_image . '-' . $model->docExtPlan->source->name . '.png';
        $doc_image->url = 'uploads/project/comp_doc_img/';
        $doc_image->id_document = $model->id_document;

        $doc_image->save();

        $src = $url;

        $img_r = imagecreatefrompng($src);

        $dst_r = ImageCreateTrueColor($w, $h);

        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y,
            $w, $h, $w, $h);

        copy($src, '../web/uploads/project/comp_doc_img/' . $doc_image->name);

        header('Content-type: image/jpeg');
        imagejpeg($dst_r, '../web/uploads/project/comp_doc_img/' . $doc_image->name, $jpeg_quality);
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (User::userCanExtractionDocument($model->id_project)) {

            if ($model->load(Yii::$app->request->post())) {

                $model->update();

                $source = $model->docExtPlan->source;
                $extension = explode('.', $source->url);
                $url = Yii::$app->request->post('image_url');

                foreach ($extension as $ext) {
                    if ($ext == "pdf" && !$source->editable) {

                        if (Yii::$app->request->post('x') != "" &&
                            Yii::$app->request->post('y') != "" &&
                            Yii::$app->request->post('h') != "" &&
                            Yii::$app->request->post('w') != "") {

                            $w = Yii::$app->request->post('w');
                            $h = Yii::$app->request->post('h');
                            $y = Yii::$app->request->post('y');
                            $x = Yii::$app->request->post('x');

                            $this->createPdfImage($w, $h, $y, $x, $url, $model);

                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            return $this->render('create', [
                                'model' => $model,
                                'source' => $model->docExtPlan->source,
                                'ext_plan' => $model->docExtPlan,
                                'project' => $model->project,
                                'extension' => $extension
                            ]);
                        }

                    } elseif ($ext == "jpg" ||
                        $ext == "jpeg" ||
                        $ext == "png") {

                        if (Yii::$app->request->post('x') != "" &&
                            Yii::$app->request->post('y') != "" &&
                            Yii::$app->request->post('h') != "" &&
                            Yii::$app->request->post('w') != "") {

                            $w = Yii::$app->request->post('w');
                            $h = Yii::$app->request->post('h');
                            $y = Yii::$app->request->post('y');
                            $x = Yii::$app->request->post('x');

                            $this->createImage($w, $h, $y, $x, $model);

                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            return $this->render('create', [
                                'model' => $model,
                                'source' => $model->docExtPlan->source,
                                'ext_plan' => $model->docExtPlan,
                                'project' => $model->project,
                                'extension' => $extension
                            ]);
                        }
                    } elseif ($ext == "pdf" && $source->editable) {
                        return $this->redirect(['index', 'id_ext_plan' => $model->id_doc_ext_plan]);
                    }
                }
            }

            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $extension = explode('.', $model->docExtPlan->source->url);

            return $this->render('create', [
                'model' => $model,
                'source' => $model->docExtPlan->source,
                'ext_plan' => $model->docExtPlan,
                'project' => $model->project,
                'extension' => $extension
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (User::userCanExtractionDocument($model->id_project)) {
            try{
                $doc_images = $model->docImages;

                if (count($doc_images) > 0) {
                    foreach ($doc_images as $doc_image) {
                        unlink($doc_image->url.$doc_image->name);
                        $doc_image->delete();
                    }
                }
            }catch (\Exception $exception){};

            $id_ext_plan = $model->id_doc_ext_plan;
            $model->delete();

            return $this->redirect(['index', 'id_ext_plan' => $id_ext_plan]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }

    public function actionPlans($id_project)
    {
        if (User::userCanExtractionDocument($id_project)) {
            $project = Project::findOne($id_project);

            $user = Yii::$app->user;

            $extraction_plans = DocExtPlan::find()->where(['id_user' => $user->id, 'id_project' => $id_project, 'finished' => false])->all();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            return $this->render('plans', [
                'plans' => $extraction_plans,
                'project' => $project,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionSources($id_doc_ext_plan)
    {
        $id_user = Yii::$app->user->identity->id_user;

        $docExtPlan = DocExtPlan::findOne(['id_doc_ext_plan' => $id_doc_ext_plan, 'id_user' => $id_user]);

        $source = $docExtPlan->source;

        $extension = explode('.', $source->url);

        foreach ($extension as $ext) {
            if ($ext == "pdf") {
                return $this->renderAjax('previewPdf', [
                    'source' => $source,
                ]);
            } elseif ($ext == "jpg" ||
                $ext == "jpeg" ||
                $ext == "png") {
                return $this->renderAjax('previewImage', [
                    'source' => $source,
                ]);
            }
        }

    }

    public function actionImageDelete($id)
    {
        $doc_image = DocImage::findOne($id);

        $document_id = $doc_image->id_document;

        try{
            unlink($doc_image->url.$doc_image->name);
        }catch (\Exception $exception){};

        $doc_image->delete();

        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->redirect(['update', 'id' => $document_id]);

    }

    /**
     * @param $id_doc_ext_plan
     * @return string|\yii\web\Response
     * @throws NotAcceptableHttpException
     */
    public function actionFinish($id_doc_ext_plan)
    {

        $ext_plan = DocExtPlan::findOne($id_doc_ext_plan);
        if (User::userCanExtractionDocument($ext_plan->id_project)) {
            if (count($ext_plan->documents) > 0) {
                $ext_plan->finished = true;
                $ext_plan->save(false);

                $project = Project::findOne(['id_project' => $ext_plan->id_project]);

                $user = Yii::$app->user;
                $plans = DocExtPlan::find()->where(['id_project' => $project->id_project, 'id_user' => $user->id, 'finished' => false])->all();

                $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                return $this->render('plans', [
                    'project' => $project,
                    'plans' => $plans
                ]);
            } else  Yii::$app->session->setFlash('error', 'El plan no puede ser finalizado si documentos complentarios extraídos');

            return $this->redirect(['index', 'id_ext_plan' => $id_doc_ext_plan]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }
}
