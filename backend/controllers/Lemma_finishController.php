<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 04/04/2019
 * Time: 9:22:PM
 */

namespace backend\controllers;


use backend\assets\AppAsset;
use backend\models\Lemma;
use backend\models\LemmaSearch;
use backend\models\Letter;
use backend\models\LexArticleReview;
use backend\models\Project;
use backend\models\ReviewLexical;
use common\models\User;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\View;

class Lemma_finishController extends Controller
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

    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $searchModel = new LemmaSearch();
            $searchModel->id_project = $id_project;
            //$searchModel->lemario = true;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $letters = Letter::find()->orderBy('letter')->all();
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'letters' => $letters,
                'model' => $this->findModelProject($id_project),
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

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

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/lemma_finish.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('view', [
                'model' => $model,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionLemma_image($id){
        $model = $this->findModel($id);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_viewer.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->renderAjax('lemma_image', [
            'model' => $model,
        ]);
    }

    public function actionRevision_lexica($id){
        $review_lexicals = ReviewLexical::findAll(['id_lex_article' => $id]);

        return $this->renderAjax('revision_lexica', [
            'review_lexicals' => $review_lexicals,
        ]);
    }

    public function actionRevision_no_edition($id){
        $lex_articule_reviews = LexArticleReview::findAll(['id_lex_article' => $id]);

        return $this->renderAjax('revision_no_edition', [
            'lex_articule_reviews' => $lex_articule_reviews,
        ]);
    }

    public function actionFinished($id){
        $model = $this->findModel($id);
        if ($model->finished == 0)
            $model->finished = 1;
        else
            $model->finished = 0;
        $model->save();
        return $this->redirect(['view', 'id' => $model->id_lemma]);
        //return $model->finished;
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if (User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $ext_lemma = $model->extracted_lemma;
            $homonym = $model->homonym;
            $model->delete();

            if ($this->isIndex($ext_lemma)){
                $ext_lemma = mb_substr($ext_lemma, 0, -1, 'utf-8');
                $this->homonym($model->id_project, $ext_lemma);
            }
            if ($homonym){
                $lemmas = Lemma::find()
                    ->andWhere(['id_project' => $id_project,])
                    ->andFilterWhere(['ilike','extracted_lemma',$ext_lemma])->all();
                if (count($lemmas) == 1){{
                    $lemmas[0]->homonym = false;
                    $lemmas[0]->save(false);
                }}
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

    private function homonym($id_project, $lemma)
    {
        $lemmas = Lemma::find()
            ->andWhere(['id_project' => $id_project, 'lemario' => true])
            ->andFilterWhere(['ilike','extracted_lemma',$lemma])->all();

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

    protected function findModelProject($id_project)
    {
        if (($model = Project::findOne($id_project)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }

    protected function findModel($id)
    {
        if (($model = Lemma::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }
}