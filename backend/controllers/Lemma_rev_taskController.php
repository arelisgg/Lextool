<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\ElementType;
use backend\models\LemmaExtPlan;
use backend\models\LemmaImage;
use backend\models\LemmaRevPlan;
use backend\models\Letter;
use backend\models\Project;
use backend\models\Source;
use common\models\User;
use Codeception\Util\FileSystem;
use http\Url;
use Yii;
use backend\models\Lemma;
use backend\models\LemmaSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;


/**
 * Lemma_rev_taskController implements the CRUD actions for Lemma model.
 */
class Lemma_rev_taskController extends Controller
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
                    'remark' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @param $id_rev_plan
     * @return string
     * @throws NotAcceptableHttpException
     */
    public function actionIndex($id_rev_plan)
    {
        $rev_plan = LemmaRevPlan::findOne($id_rev_plan);

        if (User::userCanRevitionLemma($rev_plan->id_project)) {
            $ext_plan = $rev_plan->extPlan;

            $letters = $ext_plan->letters;


            $searchModel = new LemmaSearch();
            $searchModel->id_lemma_ext_plan = $ext_plan->id_lemma_ext_plan;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $project = Project::findOne(['id_project' => $rev_plan->id_project]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/reloadLemmasRev.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('index', [
                'letters' => $letters,
                'rev_plan' => $rev_plan,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'project' => $project
            ]);

        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }


    /**
     * Displays a single Lemma model.
     * @param integer $id
     * @param $id_rev_plan
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionView($id,$id_rev_plan)
    {
        $model = Lemma::findOne($id);
        $rev_plan = LemmaRevPlan::findOne($id_rev_plan);
        $project = $model->project;

        if (User::userCanRevitionLemma($project->id_project)) {

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
                'project' => $project,
                'rev_plan' => $rev_plan
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Updates an existing Lemma model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param $id_rev_plan
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$id_rev_plan)
    {
        $model = $this->findModel($id);

        if (User::userCanRevitionLemma($model->id_project)) {

            $rev_plan = LemmaRevPlan::findOne($id_rev_plan);

            $source = $model->source;
            $letter = $model->letter;
            $project = $model->project;
            $ext_plan = $model->lemmaExtPlan;

            $elements = ElementType::find()->all();

            $ext_lemma = $model->extracted_lemma;
            if ($model->load(Yii::$app->request->post())) {

                $model->homonym = false;

                $model->save();

                if ($ext_lemma != $model->extracted_lemma){
                    $lemmas = Lemma::find()
                        ->andWhere(['id_project' => $model->id_project,])
                        ->andFilterWhere(['ilike','extracted_lemma',$ext_lemma])->all();
                    if(count($lemmas) == 1) {
                        $lemmas[0]->homonym = false;
                        $lemmas[0]->save(false);
                    }
                }

                $lemmas = Lemma::find()
                    ->andWhere(['id_project' => $model->id_project,])
                    ->andFilterWhere(['ilike','extracted_lemma', $model->extracted_lemma])->all();
                if(count($lemmas) > 1) {
                    foreach ($lemmas as $lemma) {
                        $lemma->homonym = true;
                        $lemma->save(false);
                    }
                }

                $source = $model->source;
                $extension = explode('.', $model->source->url);
                $url = Yii::$app->request->post('img_url');

                foreach ($extension as  $ext){
                    if ($ext == "pdf" && !$model->source->editable) {

                        if (Yii::$app->request->post('x') != "" &&
                            Yii::$app->request->post('y') != "" &&
                            Yii::$app->request->post('h') != "" &&
                            Yii::$app->request->post('w') != "") {

                            $w = Yii::$app->request->post('w');
                            $h = Yii::$app->request->post('h');
                            $y = Yii::$app->request->post('y');
                            $x = Yii::$app->request->post('x');

                            $this->createPdfImage($w, $h, $y, $x,$url,$model);

                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $elements = ElementType::find()->all();
                            return $this->render('update', [
                                'model' => $model,
                                'source' => $model->source,
                                'ext_plan' => $model->lemmaExtPlan,
                                'project' => $model->project,
                                'rev_plan' => $rev_plan,
                                'letter' => $model->letter,
                                'extension' => $extension,
                                'elements' => $elements
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

                            $this->createImage($w, $h, $y, $x,$model);

                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $elements = ElementType::find()->all();
                            return $this->render('update', [
                                'model' => $model,
                                'source' => $model->source,
                                'ext_plan' => $model->lemmaExtPlan,
                                'project' => $model->project,
                                'rev_plan' => $rev_plan,
                                'letter' => $model->letter,
                                'extension' => $extension,
                                'elements' => $elements
                            ]);
                        }
                    }else if ($ext == "pdf" && $model->source->editable) {
                        return $this->redirect(['index', 'id_rev_plan' => $rev_plan->id_rev_plan]);
                    }
                }

                $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                $elements = ElementType::find()->all();

                return $this->render('update', [
                    'model' => $model,
                    'source' => $model->source,
                    'ext_plan' => $model->lemmaExtPlan,
                    'project' => $model->project,
                    'rev_plan' => $rev_plan,
                    'letter' => $model->letter,
                    'extension' => $extension,
                    'elements' => $elements
                ]);
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
                'letter' => $letter,
                'rev_plan' => $rev_plan,
                'extension' => $extension,
                'elements' => $elements
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    private function createImage($w,$h,$y,$x,$model) {
        $lemma_image = new LemmaImage();

        $jpeg_quality = 100;

        $lemma_image->save();

        $lemma_image->name = $lemma_image->id_lemma_image.'-'. $model->source->name . '.jpg';
        $lemma_image->url = 'uploads/project/source_images/';
        $lemma_image->id_lemma = $model->id_lemma;

        $lemma_image->save();

        $src = '../web/uploads/project/source/'.$model->source->url;

        $img_r = imagecreatefromjpeg($src);

        $dst_r = ImageCreateTrueColor($w, $h);

        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y,
            $w, $h, $w, $h);

        copy($src, '../web/uploads/project/source_images/' . $lemma_image->name);

        header('Content-type: image/jpeg');
        imagejpeg($dst_r, '../web/uploads/project/source_images/' . $lemma_image->name, $jpeg_quality);
    }

    private function createPdfImage($w,$h,$y,$x,$url,$model)
    {
        $lemma_image = new LemmaImage();

        $jpeg_quality = 100;

        $lemma_image->save();

        $lemma_image->name = $lemma_image->id_lemma_image . '-' . $model->source->name . '.png';
        $lemma_image->url = 'uploads/project/source_images/';
        $lemma_image->id_lemma = $model->id_lemma;

        $lemma_image->save();

        $src = $url;

        $img_r = imagecreatefrompng($src);

        $dst_r = ImageCreateTrueColor($w, $h);

        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y,
            $w, $h, $w, $h);

        copy($src, '../web/uploads/project/source_images/' . $lemma_image->name);

        header('Content-type: image/jpeg');
        imagejpeg($dst_r, '../web/uploads/project/source_images/' . $lemma_image->name, $jpeg_quality);
    }

    /**
     * Deletes an existing Lemma model.
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

        if (User::userCanRevitionLemma($model->id_project)){
            try {
                $lemma_images = $model->lemmaImages;

                if (count($lemma_images) > 0) {
                    foreach ($lemma_images as $lemmaImage) {
                        unlink($lemmaImage->url.$lemmaImage->name);
                        $lemmaImage->delete();
                    }
                }
            }catch (\Exception $exception){}

            $ext_lemma = $model->extracted_lemma;
            $id_project = $model->id_project;

            if ($model->delete()){
                $lemmas = Lemma::find()
                    ->andWhere(['id_project' => $id_project,])
                    ->andFilterWhere(['ilike','extracted_lemma', $ext_lemma])->all();
                if (count($lemmas) == 1){{
                    $lemmas[0]->homonym = false;
                    $lemmas[0]->save(false);
                }}
                return "Ok";
            } else
                return "Error";
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');


    }

    /**
     * @param $id
     * @param $id_rev_plan
     * @return \yii\web\Response
     * @throws NotAcceptableHttpException
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionViewDelete($id, $id_rev_plan) {
        $model = $this->findModel($id);
        if (User::userCanRevitionLemma($model->id_project)) {
            try {
                $lemma_images = $model->lemmaImages;

                if (count($lemma_images) > 0) {
                    foreach ($lemma_images as $lemmaImage) {
                        unlink($lemmaImage->url . $lemmaImage->name);
                        $lemmaImage->delete();
                    }
                }
            } catch (\Exception $exception) {
            }
            $ext_lemma = $model->extracted_lemma;
            $id_project = $model->id_project;

            $model->delete();

            $lemmas = Lemma::find()
                ->andWhere(['id_project' => $id_project,])
                ->andFilterWhere(['ilike','extracted_lemma', $ext_lemma])->all();
            if (count($lemmas) == 1){{
                $lemmas[0]->homonym = false;
                $lemmas[0]->save(false);
            }}

            return $this->redirect(['index', 'id_rev_plan' => $id_rev_plan]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Finds the Lemma model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lemma the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lemma::findOne($id)) !== null) {
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
        if (User::userCanRevitionLemma($id_project)) {
            $project = Project::findOne($id_project);

            $user = Yii::$app->user;

            $revision_plans = LemmaRevPlan::find()->where(['id_user' => $user->id, 'id_project' => $id_project, 'finished' => false])->all();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            return $this->render('plans', [
                'plans' => $revision_plans,
                'project' => $project,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * @return string
     * @throws NotAcceptableHttpException
     */
    public function actionAprove($id, $id_rev_plan){

        $lemma = Lemma::findOne($id);
        $project = Project::findOne(['id_project' => $lemma->id_project]);

        if (User::userCanRevitionLemma($project->id_project)) {
            if ($lemma->agree)
                $lemma->agree = false;
            else
                $lemma->agree = true;
            $lemma->save(false);

            return $this->redirect(['view',
                'id' => $id,
                'id_rev_plan' => $id_rev_plan,
            ]);

        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');


    }

    public function actionImageDelete($id)
    {
        $lemma_image = LemmaImage::findOne($id);

        $lemma_id = $lemma_image->id_lemma;

        try{
            unlink($lemma_image->url.$lemma_image->name);
        }catch (\Exception $exception){};

        $lemma_image->delete();

        $id_rev_plan = Yii::$app->request->post('id_rev_plan');

        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->redirect(['update', 'id'=> $lemma_id, 'id_rev_plan' => $id_rev_plan]);

    }

    public function actionFinish($id_rev_plan) {

        $rev_plan = LemmaRevPlan::findOne($id_rev_plan);

        if (User::userCanRevitionLemma($rev_plan->id_project)) {
            $rev_plan->finished = true;
            $rev_plan->save(false);

            $project = Project::findOne(['id_project' => $rev_plan->id_project]);

            $user = Yii::$app->user;

            $plans = LemmaRevPlan::find()->where(['id_project' => $project->id_project ,'id_user' => $user->id ,'finished' => false])->all();

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
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionRemark() {


        $id_lemma = Yii::$app->request->post('id_lemma');
        $id_rev_plan = Yii::$app->request->post('id_rev_plan');

        $model = Lemma::findOne($id_lemma);

        if (User::userCanRevitionLemma($model->id_project)) {
            if (Yii::$app->request->post('remark') !== "") {
                $remark = Yii::$app->request->post('remark');

                $model->remark = $remark;
                $model->save();
            }

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            /*return $this->render('view', [
                'model' => $model,
                'project' => $project,
                'rev_plan' => $rev_plan
            ]);*/
            return $this->redirect(['view',
                'id' => $id_lemma,
                'id_rev_plan' => $id_rev_plan,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * @return string
     * @throws NotAcceptableHttpException
     */
    public function actionDeleteRemark() {

        $id_lemma = Yii::$app->request->post('id_lemma');
        $id_rev_plan = Yii::$app->request->post('id_rev_plan');

        $model = Lemma::findOne($id_lemma);

        if (User::userCanRevitionLemma($model->id_project)) {
            $model->remark = "";
            $model->save();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            /*return $this->render('view', [
                'model' => $model,
                'project' => $project,
                'rev_plan' => $rev_plan
            ]);*/
            return $this->redirect(['view',
                'id' => $id_lemma,
                'id_rev_plan' => $id_rev_plan,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }
}
