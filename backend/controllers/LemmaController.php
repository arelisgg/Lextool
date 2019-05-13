<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Letter;
use backend\models\Project;
use common\models\User;
use Yii;
use backend\models\Lemma;
use backend\models\LemmaSearch;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;

/**
 * LemmaController implements the CRUD actions for Lemma model.
 */
class LemmaController extends Controller
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
                    'delete_complete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $searchModel = new LemmaSearch();
            $searchModel->id_project = $id_project;
            $searchModel->lemario = true;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $letters = Letter::find()->orderBy('letter')->all();
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $this->findModelProject($id_project),
                'letters' => $letters,

            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    public function actionLemario($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $lemmas = Lemma::find()->where(['id_project' => $id_project, 'id_letter' => 1, 'lemario' => false, 'agree' => true])->orderBy('extracted_lemma')->all();

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/lemario.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('lemario', [
                'model' => $this->findModelProject($id_project),
                'lemmas' => $lemmas,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');


    }

    public function actionLemas($id_project, $id_letter)
    {
        $lemmas = Lemma::find()->where(['id_project' => $id_project, 'id_letter' => $id_letter, 'lemario' => false, 'agree' => true])->orderBy('extracted_lemma')->all();

        return $this->renderAjax('lemas', [
            'lemmas' => $lemmas,
        ]);
    }

    public function actionImage($id){
        $model = $this->findModel($id);

        return $this->renderAjax('image', [
            'model' => $model,
        ]);
    }

    public function actionOptions($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->lemario = true;
            $model->save();
            $this->homonym($model->id_project, $model->extracted_lemma);
            return $this->redirect(['index', 'id_project' => $model->id_project]);
        }

        return $this->renderAjax('options', [
            'model' => $model,
        ]);
    }

    private function homonym($id_project, $lemma)
    {
        $lemmas = Lemma::find()
            ->andWhere(['id_project' => $id_project, 'lemario' => true])
            ->andWhere("extracted_lemma like '".$lemma."%' and homonym = true")->all();
            //->andFilterWhere(['ilike','extracted_lemma',$lemma])->all();

        if (count($lemmas) == 1){
            $lemmas[0]->extracted_lemma = $this->deleteIndex(mb_substr($lemmas[0]->extracted_lemma, -1, 1, 'utf-8'), $lemmas[0]->extracted_lemma);;
            $lemmas[0]->save(false);
        } else if (count($lemmas) > 1){
            for ($i = 0; $i < count($lemmas); $i++){
                $lemma_temp = $this->deleteIndex(mb_substr($lemmas[$i]->extracted_lemma, -1, 1, 'utf-8'), $lemmas[$i]->extracted_lemma);
                $lemmas[$i]->extracted_lemma = $this->assignIndex($i, $lemma_temp);
                $lemmas[$i]->save(false);
            }
        }

    }

    private function deleteIndex($character, $lemma){
        $lemma_temp = $lemma;
        switch ($character){
            case '¹':
                $lemma_temp =  mb_substr($lemma, 0, -1, 'utf-8');
                break;
            case '²':
                $lemma_temp = mb_substr($lemma, 0, -1, 'utf-8');
                break;
            case '³':
                $lemma_temp = mb_substr($lemma, 0, -1, 'utf-8');
                break;
            case '⁴':
                $lemma_temp = mb_substr($lemma, 0, -1, 'utf-8');
                break;
            case '⁵':
                $lemma_temp = mb_substr($lemma, 0, -1, 'utf-8');
                break;
            case '⁶':
                $lemma_temp = mb_substr($lemma, 0, -1, 'utf-8');
                break;
            case '⁷':
                $lemma_temp = mb_substr($lemma, 0, -1, 'utf-8');
                break;
            case '⁸':
                $lemma_temp = mb_substr($lemma, 0, -1, 'utf-8');
                break;
            case '⁹':
                $lemma_temp = mb_substr($lemma, 0, -1, 'utf-8');
                break;
        }
        return $lemma_temp;
    }

    private function assignIndex($index, $lemma){
        $lemma_temp = "";
        switch ($index){
            case 0:
                $lemma_temp = $lemma.'¹';
                break;
            case 1:
                $lemma_temp = $lemma.'²';
                break;
            case 2:
                $lemma_temp = $lemma.'³';
                break;
            case 3:
                $lemma_temp = $lemma.'⁴';
                break;
            case 4:
                $lemma_temp = $lemma.'⁵';
                break;
            case 5:
                $lemma_temp = $lemma.'⁶';
                break;
            case 6:
                $lemma_temp = $lemma.'⁷';
                break;
            case 7:
                $lemma_temp = $lemma.'⁸';
                break;
            case 8:
                $lemma_temp = $lemma.'⁹';
                break;
        }
        return $lemma_temp;
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){

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
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model->lemario = false;
            if($this->isIndex($model->extracted_lemma)){
                $model->extracted_lemma = mb_substr($model->extracted_lemma, 0, -1, 'utf-8');
                $model->save();
                $this->homonym($model->id_project, $model->extracted_lemma);
            } else {
                $model->save();
            }
            return $this->redirect(['index', 'id_project' => $model->id_project]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    private function isIndex($lemma){
        $character = mb_substr($lemma, -1, 1, 'utf-8');
        $result = false;
        switch ($character){
            case '¹':
                $result = true;
                break;
            case '²':
                $result = true;
                break;
            case '³':
                $result = true;
                break;
            case '⁴':
                $result = true;
                break;
            case '⁵':
                $result = true;
                break;
            case '⁶':
                $result = true;
                break;
            case '⁷':
                $result = true;
                break;
            case '⁸':
                $result = true;
                break;
            case '⁹':
                $result = true;
                break;
        }
        return $result;
    }

    public function actionDelete_complete($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $ext_lemma = $model->extracted_lemma;
            $homonym = $model->homonym;
            $model->delete();

            if ($homonym){
                $lemmas = Lemma::find()
                    ->andWhere(['id_project' => $id_project,])
                    ->andFilterWhere(['ilike','extracted_lemma',$ext_lemma])->all();
                if (count($lemmas) == 1){{
                    $lemmas[0]->homonym = false;
                    $lemmas[0]->save(false);
                }}
            }

            return $this->redirect(['lemario', 'id_project' => $model->id_project]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    protected function findModel($id)
    {
        if (($model = Lemma::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }

    protected function findModelProject($id_project)
    {
        if (($model = Project::findOne($id_project)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }


}
