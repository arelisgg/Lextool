<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\DocImage;
use backend\models\DocRevPlan;
use backend\models\LemmaRevPlan;
use backend\models\Project;
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
 * Document_rev_taskController implements the CRUD actions for Document model.
 */
class Document_rev_taskController extends Controller
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
                    'delete' => ['get'],
                ],
            ],
        ];
    }

    /**
     * Lists all Document models.
     * @param $id_rev_plan
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionIndex($id_rev_plan)
    {
        $rev_plan = DocRevPlan::findOne($id_rev_plan);
        $project = $rev_plan->project;

        if (User::userCanRevitionDocument($project->id_project)) {

            $searchModel = new DocumentSearch();
            $searchModel->id_project = $rev_plan->id_project;
            $searchModel->id_doc_type = $rev_plan->extDocPlan->id_doc_type;
            $searchModel->id_doc_ext_plan = $rev_plan->extDocPlan->id_doc_ext_plan;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'rev_plan' => $rev_plan,
                'project' => $project
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Displays a single Document model.
     * @param integer $id
     * @param $id_rev_plan
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionView($id,$id_rev_plan)
    {

        $model = Document::findOne($id);

        $rev_plan = DocRevPlan::findOne($id_rev_plan);
        $project = Project::findOne(['id_project' => $rev_plan->id_project]);
        if (User::userCanRevitionDocument($project->id_project)) {

            if (Yii::$app->request->post()) {
                if (!$model->reviewed) {
                    $model->reviewed = true;
                    $model->save(false);
                } else {
                    $model->reviewed = false;
                    $model->save(false);
                }
            }

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
                'rev_plan' => $rev_plan,
                'project' => $project
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    public function actionAprove($id,$id_rev_plan)
    {
        $model = Document::findOne($id);

        if (User::userCanRevitionDocument($model->id_project)) {


            if (!$model->reviewed) {
                $model->reviewed = true;
                $model->save(false);
            } else {
                $model->reviewed = false;
                $model->save(false);
            }

            return $this->redirect(['view',
                'id' => $id,
                'id_rev_plan' => $id_rev_plan,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }


    public function actionUpdate($id,$id_rev_plan)
    {
        $model = $this->findModel($id);

        if (User::userCanRevitionDocument($model->id_project)) {

            $rev_plan = DocRevPlan::findOne($id_rev_plan);

            $source = $model->docExtPlan->source;
            $project = $model->project;
            $ext_plan = $model->docExtPlan;

            if ($model->load(Yii::$app->request->post())) {

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

                            return $this->render('update', [
                                'model' => $model,
                                'source' => $model->docExtPlan->source,
                                'ext_plan' => $model->docExtPlan,
                                'rev_plan' => $rev_plan,
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

                            return $this->render('update', [
                                'model' => $model,
                                'source' => $model->docExtPlan->source,
                                'ext_plan' => $model->docExtPlan,
                                'rev_plan' => $rev_plan,
                                'project' => $model->project,
                                'extension' => $extension
                            ]);
                        }
                    } elseif ($ext == "pdf" && $source->editable) {
                        return $this->redirect(['index', 'id_rev_plan' => $rev_plan->id_rev_plan]);
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

            $extension = explode('.', $source->url);

            return $this->render('update', [
                'model' => $model,
                'source' => $source,
                'ext_plan' => $ext_plan,
                'project' => $project,
                'rev_plan' => $rev_plan,
                'extension' => $extension
            ]);
        } else
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
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id,$id_rev_plan)
    {
        $model = $this->findModel($id);

        if (User::userCanRevitionDocument($model->id_project)) {

            $doc_images = $model->docImages;

            try {
                if (count($doc_images) > 0) {
                    foreach ($doc_images as $doc_image) {
                        unlink($doc_image->url . $doc_image->name);
                        $doc_image->delete();
                    }
                }
            } catch (\Exception $exception) {
            }

            $model->delete();

            return $this->redirect(['index', 'id_rev_plan' => $id_rev_plan]);
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id_project
     * @return string
     * @throws NotAcceptableHttpException
     */
    public function actionPlans($id_project)
    {
        if (User::userCanRevitionDocument($id_project)) {
            $project = Project::findOne($id_project);

            $user = Yii::$app->user;

            $rev_plans = DocRevPlan::find()->where(['id_user' => $user->id , 'id_project' => $id_project, 'finished' => false])->all();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            return $this->render('plans', [
                'plans' => $rev_plans,
                'project' => $project,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * @param $id_rev_plan
     * @return string
     * @throws NotAcceptableHttpException
     */
    public function actionFinish($id_rev_plan){
        $rev_plan = DocRevPlan::findOne($id_rev_plan);

        if (User::userCanRevitionDocument($rev_plan->id_project)) {
            $rev_plan->finished = true;
            $rev_plan->save(false);

            $project = $rev_plan->project;

            $user = Yii::$app->user;

            $plans = DocRevPlan::find()->where(['id_project' => $project->id_project ,'id_user' => $user->id ,'finished' => false])->all();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('plans', [
                'project' => $project,
                'plans' => $plans
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionImageDelete($id, $id_rev_plan)
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

        return $this->redirect(['update', 'id' => $document_id, 'id_rev_plan' => $id_rev_plan]);

    }

}
