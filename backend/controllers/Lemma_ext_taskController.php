<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\ElementType;
use backend\models\LemmaExtPlan;
use backend\models\LemmaImage;
use backend\models\Letter;
use backend\models\Project;
use backend\models\Source;
use common\models\User;
use Codeception\Util\FileSystem;
use http\Exception;
use kartik\mpdf\Pdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use SebastianBergmann\Timer\Timer;
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
 * Lemma_ext_taskController implements the CRUD actions for Lemma model.
 */
class Lemma_ext_taskController extends Controller
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
                    'upload-pdf-image' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Lemma models.
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionIndex($id_ext_plan)
    {

        $searchModel = new LemmaSearch();
        $searchModel->id_lemma_ext_plan = $id_ext_plan;

        $ext_plan = LemmaExtPlan::findOne($id_ext_plan);

        $letters = $ext_plan->letters;

        $currentLetter = $letters[0];

        $project = Project::findOne(['id_project' => $ext_plan->id_project]);

//        if (User::userCanExtractionLemma($project->id_project)) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            //$this->view->registerJsFile(Yii::$app->homeUrl . 'js/reloadLemmas.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/loadSourcesLemma.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            return $this->render('index', [
                'letters' => $letters,
                'currentLetter' => $currentLetter,
                'ext_plan' => $ext_plan,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'project' => $project
            ]);
//        }else
//            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Displays a single Lemma model.
     * @param integer $id
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionView($id)
    {
        $model = Lemma::findOne($id);

        $ext_plan = LemmaExtPlan::findOne(['id_lemma_ext_plan' => $model->id_lemma_ext_plan]);
        $project = Project::findOne(['id_project' => $model->id_project]);

        if (User::userCanExtractionLemma($project->id_project)) {
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

            return $this->render('view', compact('model', 'ext_plan', 'project'));
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Creates a new Lemma model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $cadena
     * @return mixed
     */
    function quitar_tildes($cadena) {
        $no_permitidas= array ("á","é","í","ó","ú","ä","ë","ï","ö","ü","à","è","ì","ò","ù","Á","É","Í","Ó","Ú","À","È","Ì","Ò","Ù","Ä","Ë","Ï","Ö","Ü");
        $permitidas =   array ("a","e","i","o","u","a","e","i","o","u","a","e","i","o","u","A","E","I","O","U","A","E","I","O","U","A","E","I","O","U");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }

    public function actionCreate()
    {
        $model = new Lemma();

        if ($model->load(Yii::$app->request->post())){
            $id_source = $model->id_source;
            $id_letter = $model->id_letter;
            $id_project = $model->id_project;
        } else {
            $id_source = Yii::$app->request->post('id_source');
            $id_letter = Yii::$app->request->post('id_letter');
            $id_project = Yii::$app->request->post('id_project');
        }
        $source = Source::findOne(['id_source' => $id_source]);
        $letter = Letter::findOne(['id_letter' => $id_letter]);
        $project = Project::findOne(['id_project' => $id_project]);


        if (User::userCanExtractionLemma($project->id_project)) {
            $id_ext_plan = Yii::$app->request->post('id_ext_plan');
            $ext_plan = LemmaExtPlan::findOne($id_ext_plan);

            $elements = ElementType::find()->all();

            if ($model->load(Yii::$app->request->post())) {

                $id_lemma = Yii::$app->request->post('id_lemma');
                $substructure = Yii::$app->request->post('substructure');

                if ($id_lemma != null && $id_lemma != ""){
                    $model = $this->findModel($id_lemma);
                }

                $model->substructure = $substructure;
                $model->agree = false;
                $model->finished = false;
                $model->homonym = false;
                $cadena = $this->quitar_tildes($model->extracted_lemma);
                $letra = mb_substr($cadena, 0, 1, 'utf-8');
                $modelLetter = Letter::find()->where("letter = upper('".$letra."')")->one();
                $model->id_letter = $modelLetter->id_letter;

                $model->save();


                $lemmas = Lemma::find()
                    ->andWhere(['id_project' => $id_project,])
                    ->andFilterWhere(['ilike','extracted_lemma',$model->extracted_lemma])->all();
                if(count($lemmas) > 1) {
                    foreach ($lemmas as $lemma) {
                        $lemma->homonym = true;
                        $lemma->save(false);
                    }
                }

                $source = $model->source;
                $extension = explode('.', $model->source->url);
                $url = Yii::$app->request->post('img_url');



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

                            $this->createPdfImage($w, $h, $y, $x,$url,$model);

                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-image-crop.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                            $elements = ElementType::find()->all();

                            return $this->render('create', [
                                'model' => $model,
                                'source' => $model->source,
                                'ext_plan' => $model->lemmaExtPlan,
                                'project' => $model->project,
                                'letter' => $model->letter,
                                'extension' => $extension,
                                'elements' => $elements
                            ]);
                        }

                    }
                    elseif ($ext == "jpg" ||
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

                            return $this->render('create', [
                                'model' => $model,
                                'source' => $model->source,
                                'ext_plan' => $model->lemmaExtPlan,
                                'project' => $model->project,
                                'letter' => $model->letter,
                                'extension' => $extension,
                                'elements' => $elements
                            ]);
                        }
                    } elseif ($ext == "pdf" && $source->editable) {
                        return $this->redirect(['index', 'id_ext_plan' => $model->id_lemma_ext_plan]);
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

                return $this->render('create', [
                    'model' => $model,
                    'source' => $model->source,
                    'ext_plan' => $model->lemmaExtPlan,
                    'project' => $model->project,
                    'letter' => $model->letter,
                    'extension' => $extension,
                    'elements' => $elements
                ]);

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
                'letter' => $letter,
                'extension' => $extension,
                'elements' => $elements
            ]);

        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    //Store
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
     * Updates an existing Lemma model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $source = $model->source;
        $letter = $model->letter;
        $project = $model->project;

        if (User::userCanExtractionLemma($project->id_project)){
            $ext_plan = $model->lemmaExtPlan;

            $elements = ElementType::find()->all();

            $ext_lemma = $model->extracted_lemma;
            if ($model->load(Yii::$app->request->post())) {

                $substructure = Yii::$app->request->post('substructure');
                $model->substructure = $substructure;
                $model->homonym = false;

                $model->save();

                if ($ext_lemma != $model->extracted_lemma){
                    $lemmas = Lemma::find()
                        ->andWhere(['id_project' => $model->id_project,])
                        ->andFilterWhere(['ilike','extracted_lemma', $ext_lemma])->all();
                    if(count($lemmas) == 1) {
                        $lemmas[0]->homonym = false;
                        $lemmas[0]->save(false);
                    }
                }

                $lemmas = Lemma::find()
                    ->andWhere(['id_project' => $model->id_project,])
                    ->andFilterWhere(['ilike','extracted_lemma',$model->extracted_lemma])->all();
                if(count($lemmas) > 1) {
                    foreach ($lemmas as $lemma) {
                        $lemma->homonym = true;
                        $lemma->save(false);
                    }
                }


                $source = $model->source;
                $extension = explode('.', $model->source->url);
                $url = Yii::$app->request->post('img_url');



                foreach ($extension as $ext) {
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
                            return $this->render('create', [
                                'model' => $model,
                                'source' => $model->source,
                                'ext_plan' => $model->lemmaExtPlan,
                                'project' => $model->project,
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
                            return $this->render('create', [
                                'model' => $model,
                                'source' => $model->source,
                                'ext_plan' => $model->lemmaExtPlan,
                                'project' => $model->project,
                                'letter' => $model->letter,
                                'extension' => $extension,
                                'elements' => $elements
                            ]);
                        }
                    } else if($ext == "pdf" && $model->source->editable) {
                        return $this->redirect(['index', 'id_ext_plan' => $model->id_lemma_ext_plan]);
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

                return $this->render('create', [
                    'model' => $model,
                    'source' => $model->source,
                    'ext_plan' => $model->lemmaExtPlan,
                    'project' => $model->project,
                    'letter' => $model->letter,
                    'extension' => $extension,
                    'elements' => $elements
                ]);

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
                'letter' => $letter,
                'extension' => $extension,
                'elements' => $elements
            ]);
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

        throw new NotFoundHttpException('La página pedida no existe.');
    }

    public function actionSources($id_lemma_ext_plan)
    {

        $id_user = Yii::$app->user->identity->id_user;

        $lemmaExtPlans = LemmaExtPlan::find()->where(['id_lemma_ext_plan' => $id_lemma_ext_plan, 'id_user' => $id_user])->all();

        $sources = [];

        foreach ($lemmaExtPlans as $lemmaExtPlan) {
            foreach ($lemmaExtPlan->sources as $source) {
                array_push($sources, $source);
            }
        }

        return $this->renderAjax('sources', [
            'sources' => $sources,
        ]);
    }

    public function actionPreview($source)
    {
        $source = Source::findOne(['name' => $source]);

        $extension = explode('.', $source->url);

        foreach ($extension as $ext) {
            if ($ext == "pdf") {
                return $this->renderAjax('previewPdf', [
                    'source' => $source
                ]);
            } elseif ($ext == "jpg" ||
                $ext == "jpeg" ||
                $ext == "bmp" ||
                $ext == "png" && $source->editable == false) {
                return $this->renderAjax('previewImage', [
                    'source' => $source
                ]);
            }
        }
    }

    /**
     * @param $id_project
     * @return string
     * @throws NotAcceptableHttpException
     */
    public function actionPlans($id_project)
    {
        if (User::userCanExtractionLemma($id_project)) {
            $project = Project::findOne($id_project);

            $user = Yii::$app->user;

            $extraction_plans = LemmaExtPlan::find()->where(['id_user' => $user->id , 'id_project' => $id_project ,'finished' => false])->all();

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

    public function actionUpdateImageDelete($id)
    {
        $lemma_image = LemmaImage::findOne($id);

        $lemma_id = $lemma_image->id_lemma;

        try {
            unlink($lemma_image->url.$lemma_image->name);
        }catch (\Exception $exception){};

        $lemma_image->delete();

        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/image-crop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/jcrop/css/jquery.Jcrop.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/pdf-to-img.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/form-show-hide.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.color.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/jcrop/js/jquery.Jcrop.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->redirect(['update', 'id' => $lemma_id]);
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

        if (User::userCanExtractionLemma($model->id_project)) {
            $ext_lemma = $model->extracted_lemma;
            $id_project = $model->id_project;

            try {
                $lemma_images = $model->lemmaImages;

                if (count($lemma_images) > 0) {
                    foreach ($lemma_images as $lemmaImage) {
                        unlink($lemmaImage->url.$lemmaImage->name);
                        $lemmaImage->delete();
                    }
                }
            }catch (\Exception $exception){}

            $id_ext_plan = $model->id_lemma_ext_plan;

            $model->delete();

            $lemmas = Lemma::find()
                ->andWhere(['id_project' => $id_project,])
                ->andFilterWhere(['ilike','extracted_lemma',$ext_lemma])->all();
            if (count($lemmas) == 1){{
                $lemmas[0]->homonym = false;
                $lemmas[0]->save(false);
            }}

            return $this->redirect(['index', 'id_ext_plan' => $id_ext_plan]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * @param $id_lemma_ext_plan
     * @return string|\yii\web\Response
     * @throws NotAcceptableHttpException
     */
    public function actionFinish($id_lemma_ext_plan) {


        $ext_plan = LemmaExtPlan::findOne(['id_lemma_ext_plan' => $id_lemma_ext_plan]);
        if (User::userCanExtractionLemma($ext_plan->id_project)) {
            if (count($ext_plan->lemmas) > 0) {
                $ext_plan->finished = true;
                $ext_plan->save(false);

                $project = Project::findOne(['id_project' => $ext_plan->id_project]);

                $user = Yii::$app->user;
                $plans = LemmaExtPlan::find()->where(['id_project' => $project->id_project, 'id_user' => $user->id, 'finished' => false])->all();

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
            } else
                Yii::$app->session->setFlash('error', 'El plan no puede ser finalizado sin lemas extraídos');

            return $this->redirect(['index', 'id_ext_plan' => $id_lemma_ext_plan]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

}


