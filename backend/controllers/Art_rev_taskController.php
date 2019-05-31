<?php
/**
 * Created by PhpStorm.
 * User: Alvaro
 * Date: 03/04/2019
 * Time: 9:58:AM
 */

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Lemma;
use backend\models\LexArticleReview;
use backend\models\Model;
use backend\models\Project;
use backend\models\ReviewCriteria;
use backend\models\ReviewLexical;
use backend\models\RevisionPlan;
use common\models\User;
use frontend\models\Search;
use Yii;
use yii\filters\VerbFilter;
use backend\models\LemmaSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\View;
use antkaz\vue\VueAsset;
use backend\models\DictionaryLink;
use backend\models\Element;
use backend\models\ElementSeparator;
use backend\models\LexArticle;
use backend\models\LexArticleElement;
use backend\models\Separator;
use backend\models\SubModel;
use backend\models\SubModelElement;
use backend\models\SubModelSeparator;
use yii\helpers\Json;


/**
 * Art_rev_taskController implements the CRUD actions for Lemma model.
 */
class Art_rev_taskController extends Controller
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

    /**
     * @param $id_project
     * @return string
     * @throws NotAcceptableHttpException
     */
    public function actionPlans($id_project)
    {
        if (User::userCanRevition($id_project)) {
            $project = Project::findOne($id_project);

            $user = Yii::$app->user;

            $revition_plans_lexical = RevisionPlan::find()->where(['id_user' => $user->id, 'type'=>'Léxica', 'id_project' => $id_project, 'finished' => false])->all();
            $revition_plans_edition = RevisionPlan::find()->where(['id_user' => $user->id, 'type'=>'Normal', 'edition' => 1, 'id_project' => $id_project, 'finished' => false])->all();
            $revition_plans_noedition = RevisionPlan::find()->where(['id_user' => $user->id, 'type'=>'Normal', 'edition' => 0, 'id_project' => $id_project, 'finished' => false])->all();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('plans', [
                'revition_plans_lexical' => $revition_plans_lexical,
                'revition_plans_edition' => $revition_plans_edition,
                'revition_plans_noedition' => $revition_plans_noedition,
                'project' => $project,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * @param $id_revision_plan
     * @return string
     * @throws NotAcceptableHttpException
     * @throws NotFoundHttpException
     */
    public function actionIndexlexical($id_revision_plan)
    {
        $revision_plan = $this->findModelRevitionPlan($id_revision_plan);
        $letters = $revision_plan->letters;
        $project = $revision_plan->project;

        if (User::userCanRevition($project->id_project)) {
            $searchModel = new LemmaSearch();
            $searchModel->lexArtFinished = true;
            $searchModel->letters = $letters;
            $searchModel->id_project = $project->id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('indexlexical', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'letters' => $letters,
                'revision_plan' => $revision_plan,
                'project' => $project,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * @param $id_lemma
     * @param $id_revision_plan
     * @return string|\yii\web\Response
     * @throws NotAcceptableHttpException
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionRevitionlexical($id_lemma, $id_revision_plan)
    {
        $model = $this->findModelLemma($id_lemma);
        $project = $model->project;

        if (User::userCanRevition($project->id_project)) {
            $revision_plan = $this->findModelRevitionPlan($id_revision_plan);

            $lex_aricle = $model->lexArticle;
            $reviewLexicals = count($lex_aricle->reviewLexicals) > 0 ? $lex_aricle->reviewLexicals : [new ReviewLexical()];

            if ($lex_aricle->load(Yii::$app->request->post())) {
                $oldIDs = ArrayHelper::map($reviewLexicals, 'id_review_lexical', 'id_review_lexical');
                $reviewLexicals = Model::createMultiple(ReviewLexical::classname(), $reviewLexicals,'id_review_lexical');
                Model::loadMultiple($reviewLexicals, Yii::$app->request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($reviewLexicals, 'id_review_lexical', 'id_review_lexical')));

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            ReviewLexical::deleteAll(['id_review_lexical' => $deletedIDs]);
                        }
                        foreach ($reviewLexicals as $reviewLexical) {
                            $reviewLexical->id_lex_article = $lex_aricle->id_lex_article;

                            if (! ($flag = $reviewLexical->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
                $lex_aricle->save(false);

                return $this->redirect(['indexlexical', 'id_revision_plan' => $id_revision_plan]);
            }

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/revitionlexical.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            //iCheck
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/iCheck/square/blue.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/iCheck/icheck.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_icheck.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            return $this->render('revitionlexical', [
                'model' => $model,
                'project' => $project,
                'revision_plan' => $revision_plan,
                'reviewLexicals' => $reviewLexicals,
                'lex_aricle' => $lex_aricle,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * @param $id_project
     * @param $word
     * @return string
     * @throws NotAcceptableHttpException
     */
    public function actionSearch($id_project, $word)
    {
        if (User::userCanRevition($id_project)) {
            $model = new Search();

            $model->lemma = $word;
            $model->id_project = $id_project;
            $lemmas = Lemma::find()
                ->andFilterWhere(['ilike', 'extracted_lemma', $word])
                ->andWhere(["lemario" => true])
                //->andWhere(["finished" => true])
                ->andWhere(["id_project" => $id_project])
                ->orderBy('extracted_lemma')->limit(100)->all();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_END]);

            return $this->renderAjax('search', ['lemmas' => $lemmas, 'model' => $model]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * @param $id
     * @return string
     * @throws NotAcceptableHttpException
     * @throws NotFoundHttpException
     */
    public function actionOptions($id){
        $model = $this->findModelLemma($id);

        if (User::userCanRevition($model->id_project)) {
            $illustrations = $model->illustrations;

            return $this->renderAjax('options', [
                'model' => $model,
                'illustrations' => $illustrations,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionIndexedition($id_revision_plan)
    {
        $revision_plan = $this->findModelRevitionPlan($id_revision_plan);
        $letters = $revision_plan->letters;
        $project = $revision_plan->project;

        if (User::userCanRevition($project->id_project)) {

            $searchModel = new LemmaSearch();
            $searchModel->lexArtFinished = true;
            $searchModel->letters = $letters;
            $searchModel->id_project = $project->id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('indexedition', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'letters' => $letters,
                'revision_plan' => $revision_plan,
                'project' => $project,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * @param $id_revision_plan
     * @return string
     * @throws NotAcceptableHttpException
     * @throws NotFoundHttpException
     */
    public function actionIndexnoedition($id_revision_plan)
    {
        $revision_plan = $this->findModelRevitionPlan($id_revision_plan);
        $letters = $revision_plan->letters;
        $project = $revision_plan->project;

        if (User::userCanRevition($project->id_project)) {
            $searchModel = new LemmaSearch();
            $searchModel->lexArtFinished = true;
            $searchModel->letters = $letters;
            $searchModel->id_project = $project->id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('indexnoedition', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'letters' => $letters,
                'revision_plan' => $revision_plan,
                'project' => $project,
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * @param $id_lemma
     * @param $id_revision_plan
     * @return string|\yii\web\Response
     * @throws NotAcceptableHttpException
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionRevitionnoedition($id_lemma, $id_revision_plan)
    {
        $model = $this->findModelLemma($id_lemma);
        $project = $model->project;

        if (User::userCanRevition($project->id_project)) {
            $revision_plan = $this->findModelRevitionPlan($id_revision_plan);
            $reviewCriterias = $revision_plan->reviewCriterias;

            $lex_aricle = $model->lexArticle;
            $lex_aricle_reviews = count($lex_aricle->lexArticleReviews) > 0? $lex_aricle->lexArticleReviews : [new LexArticleReview()];

            if ($lex_aricle->load(Yii::$app->request->post())) {
                $oldIDs = ArrayHelper::map($lex_aricle_reviews, 'id_lex_article_review', 'id_lex_article_review');
                $lex_aricle_reviews = Model::createMultiple(LexArticleReview::classname(), $lex_aricle_reviews,'id_lex_article_review');
                Model::loadMultiple($lex_aricle_reviews, Yii::$app->request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($lex_aricle_reviews, 'id_lex_article_review', 'id_lex_article_review')));

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            LexArticleReview::deleteAll(['id_lex_article_review' => $deletedIDs]);
                        }
                        foreach ($lex_aricle_reviews as $lex_aricle_review) {
                            $lex_aricle_review->id_lex_article = $lex_aricle->id_lex_article;

                            if (! ($flag = $lex_aricle_review->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
                $lex_aricle->save(false);

                return $this->redirect(['indexnoedition', 'id_revision_plan' => $id_revision_plan]);
            }
            //iCheck
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/iCheck/square/blue.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/iCheck/icheck.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_icheck.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/revitionnoedition.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('revitionnoedition', [
                'model' => $model,
                'project' => $project,
                'revision_plan' => $revision_plan,
                'lex_aricle_reviews' => $lex_aricle_reviews,
                'reviewCriterias' => $reviewCriterias,
                'lex_aricle' => $lex_aricle,

            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * @param $id_revision_plan
     * @return \yii\web\Response
     * @throws NotAcceptableHttpException
     */
    public function actionFinish($id_revision_plan){

        $plan = RevisionPlan::findOne($id_revision_plan);
        if (User::userCanRevition($plan->id_project)) {
            $plan->finished = true;
            $plan->save(false);

            return $this->redirect(['plans', 'id_project' => $plan->id_project]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }



    protected function findModelLemma($id_lemma)
    {
        if (($model = Lemma::findOne($id_lemma)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('No tiene permitido ejecutar esta acción.');
    }

    protected function findModelProject($id_project)
    {
        if (($model = Project::findOne($id_project)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelRevitionPlan($id_revision_plan)
    {
        if (($model = RevisionPlan::findOne($id_revision_plan)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    //Acciones Adonai

    /**
     * @param $id
     * @param $id_revision_plan
     * @return string
     * @throws NotAcceptableHttpException
     */
    public function actionUpdate($id, $id_revision_plan) {
        $plan = RevisionPlan::findOne($id_revision_plan);

        $lemma = Lemma::findOne($id);
        $lex_article = $lemma->lexArticle;
        $project = $plan->project;

        if (User::userCanRevition($project->id_project)) {
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/revision.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/revision_update/axios.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/revision_update/lodash.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/revision_update/lex-article-element.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/revision_update/modal-lex-article-element.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction/autocomplete.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/revision_update/app.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);

            return $this->render('update', compact('lex_article','project','plan'));
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    public function actionArea($id_lemma,$id_lex_article) {

        $lex_articles = LexArticle::find()->all();
        foreach ($lex_articles as $article){
            if ($article->article == null) {
                $article->delete();
            }
        }

        $lemma = Lemma::findOne($id_lemma);

        $ordered = [];
        $keys = [];

        $model = [];
        $model_keys = [];

        $i = 1;

        $submodels = SubModel::find()
            ->where(['id_project' => $lemma->id_project])
            ->andWhere(['>','order', 0])
            ->orderBy('order')->all();

        foreach ($submodels as $submodel){

            $section = [];
            $section_keys = [];

            try {
                $submodel_separators = $submodel->subModelSeparators;
                foreach ($submodel_separators as $separator) {
                    $sep = Separator::findOne($separator->id_separator);
                    array_push($model_keys,'sub_model_separator-'.$submodel->id_sub_model);
                    array_push($model,$sep);
                }
            }catch (\Exception $exception){
                $exception->getTrace();
            }

            $submodel_elements = SubModelElement::find()->where(['id_sub_model' => $submodel->id_sub_model])->orderBy('order')->all();

            array_push($section_keys,'sub_model');
            array_push($section,$submodel);

            $k = 1;

            foreach ($submodel_elements as $element) {

                $element_key_id = strval($k);

                $element_key = 'element-'.$element_key_id;
                array_push($section_keys,$element_key);

                $element = Element::findOne($element->id_element);
                array_push($section, $element);

                try{
                    $element_separator = ElementSeparator::findOne(['id_element' => $element->id_element, 'id_sub_model' => $submodel->id_sub_model]);
                    $sep_id = $element_separator->id_element;
                    $element_separator = Separator::findOne(['id_separator' => $element_separator->id_separator]);

                    if ($element_separator != null) {
                        array_push($section_keys, 'element_separator-'.strval($sep_id));
                        array_push($section, $element_separator);
                    }

                }catch (\Exception $exception){}

                $element_type = $element->elementType->name;
                $element_type_key = 'element_type-'.$element_key_id;

                array_push($section_keys,$element_type_key);
                array_push($section, $element_type);

                $k++;

            }

            $section = array_combine($section_keys,$section);

            $submodel_key_id = strval($i);
            $submodel_key = "submodel-".$submodel_key_id;

            array_push($model_keys,$submodel_key);
            array_push($model,$section);
            $i++;

        }

        $model = array_combine($model_keys,$model);

        array_push($keys,'model');
        array_push($ordered,$model);

        if ($id_lex_article == '') {
            $lex_article = new LexArticle();
            $lex_article->save(false);
        }else {
            $lex_article = LexArticle::findOne($id_lex_article);
        }

        array_push($keys, 'lemma');
        array_push($ordered,$lemma);

        array_push($keys, 'lex_article');
        array_push($ordered,$lex_article);

        $ordered = array_combine($keys,$ordered);

        $response = Yii::$app->response;
        $response->data = $ordered;
        $response->format = 'json';

        return $ordered;

    }

    public function actionDictionaries() {
        $dictionaries = DictionaryLink::find()->all();

        $response = Yii::$app->response;
        $response->data = $dictionaries;
        $response->format = 'json';

        return $response;
    }

    public function actionElement($id_element, $id_lemma, $id_sub_model){
        $lemma = Lemma::findOne($id_lemma);

        $element = Element::findOne($id_element);

        $sub_elements = $element->subElements;
        $sub_model = SubModel::findOne($id_sub_model);

        $type = "";
        $repeat = false;
        $required = false;

        $keys = ['lemma','element','sub_elements_types','sub_model', 'type','repeat','required'];
        $res = [];

        array_push($res,$lemma);

        $element_arr = [];
        $element_arr_keys = ['id_element','type'];
        array_push($element_arr, $element->id_element);
        array_push($element_arr, $element->elementType->name);

        $element_arr = array_combine($element_arr_keys,$element_arr);

        array_push($res,$element_arr);

        $sub_element_types_keys = [];
        $sub_element_types = [];

        $sub_element_index = 1;
        $sub_element_type_index = 1;

        foreach ($sub_elements as $sub_element_type) {

            $sub_element_ind = strval($sub_element_index);
            $sub_element_type_ind = strval($sub_element_type_index);

            array_push($sub_element_types_keys, 'sub_element_type-'.$sub_element_ind);
            array_push($sub_element_types,$sub_element_type->elementSubType);
            array_push($sub_element_types_keys, 'sub_element-'.$sub_element_type_ind);
            array_push($sub_element_types,$sub_element_type);

            $sub_element_index++;
            $sub_element_type_index++;
        }

        $sub_element_types = array_combine($sub_element_types_keys,$sub_element_types);

        array_push($res,$sub_element_types);
        array_push($res,$sub_model);

        if ($element->property == "Descripción") {
            $type = "desc";
        } elseif ($element->property == "Redacción") {
            $type = "red";
        }elseif ($element->property == 'Lema-entrada') {
            $type = "lemma";
        }elseif ($element->property == 'Lema-remisión') {
            $type = "reference_lemma";
        }

        array_push($res,$type);

        if ($sub_model->repeat) {
            $repeat = true;
        }

        array_push($res,$repeat);

        if ($sub_model->required) {
            $required = true;
        }

        array_push($res,$required);

        $res = array_combine($keys,$res);

        $response = Yii::$app->response;
        $response->data = $res;
        $response->format = 'json';
        return $response;
    }

    public function actionGetSubmodelSeparator($id_sub_model) {
        $result = '';
        try{
            $sub_model_separator = SubModelSeparator::findOne(['id_sub_model' => $id_sub_model]);
            if ($sub_model_separator !== null){
                $result = Separator::findOne(['id_separator'=> $sub_model_separator->id_separator]);
            }
        }catch (\Exception $exception){}

        $response = Yii::$app->response;
        $response->format = 'json';
        $response->data = $result;
        return $response;
    }
    public function actionGetElementSeparator($id_sub_model,$id_element) {
        $result = '';
        try{
            $element_separator = ElementSeparator::findOne(['id_sub_model' => $id_sub_model,'id_element'=>$id_element]);
            if ($element_separator !== null){
                $result = Separator::findOne(['id_separator'=> $element_separator->id_separator]);
            }
        }catch (\Exception $exception){}

        $response = Yii::$app->response;
        $response->format = 'json';
        $response->data = $result;
        return $response;
    }

    public function actionCreateElement($id_lex_article,$id_element,$lemma,$separator_id,$sub_type,$text,
                                        $sub_model_separator_id,
                                        $order,$id_sub_model) {

        $lex_article_element = new LexArticleElement();
        $lex_article_element->id_lex_article = $id_lex_article;
        $lex_article_element->id_element = $id_element;
        $lex_article_element->id_sub_element = $sub_type;
        $lex_article_element->id_sub_model = $id_sub_model;
        $lex_article_element->order = $order;

        if ($lemma != "") {
            $element = Element::findOne($id_element);
            if (strtolower($element->elementType->name) == strtolower('Lema')) {
                $lex_article_element->element = $lemma;
            }
        }else {
            $lex_article_element->element = $text;
        }

        $lex_article_element->save();

        $result = [];

        $keys = ['lex_article_element','element','sub_model'];
        array_push($result,$lex_article_element);
        array_push($result,$lex_article_element->element0);
        array_push($result, $lex_article_element->subModel);

        if($lex_article_element->subElement != null) {
            array_push($keys,'sub_element');
            array_push($result,$lex_article_element->subElement);
            array_push($keys,'sub_element_type');
            array_push($result,$lex_article_element->subElement->elementSubType->name);
        }

        $sub_model_separator = '';

        if ($lex_article_element->subModel->repeat) {
            try{
                $sub_model_separator = SubModelSeparator::findOne(['id_sub_model' => $id_sub_model]);
                $sub_model_separator = Separator::findOne(['id_separator' => $sub_model_separator->id_separator]);

                array_push($keys,'sub_model_separator');
                array_push($result,$sub_model_separator);
            }catch (\Exception $exception){}

        }elseif ($sub_model_separator_id != "") {
            $sub_model_separator = Separator::findOne($sub_model_separator_id);
            array_push($keys,'sub_model_separator');
            array_push($result,$sub_model_separator);
        }else {
            array_push($keys,'sub_model_separator');
            array_push($result,$sub_model_separator);
        }

        array_push($keys,'element_separator');

        $separator = "";

        if ($separator_id != "") {
            $separator = Separator::findOne($separator_id);
        }
        array_push($result,$separator);

        $result = array_combine($keys,$result);

        $response = Yii::$app->response;
        $response->data = $result;
        $response->format = 'json';
        return $response;

    }

    public function actionChangeLexArtElem($lex_article_elements) {

        $lex_article_elements = Json::decode($lex_article_elements);

        $order = 1;

        foreach ($lex_article_elements as $lex_article_element) {
            $element = LexArticleElement::findOne($lex_article_element);
            $element->order = $order;
            $element->save();
            $order++;
        }

        return Yii::$app->response->statusCode;

    }

    /**
     * @param $lex_article_element
     * @return int|mixed
     */
    public function actionDeleteLexArtElem($lex_article_element) {
        $lex_article_element = Json::decode($lex_article_element);

        $element = LexArticleElement::findOne($lex_article_element);
        try {
            $element->delete();
        } catch (StaleObjectException $e) {
        } catch (\Throwable $e) {
        }

        return Yii::$app->response->statusCode;
    }

    public function actionGetElement($id_element) {
        $element = Element::findOne($id_element);

        $result = [];
        $keys = ['element','sub_type'];

        array_push($result,$element);
        array_push($result,$element->elementType->name);

        $result = array_combine($keys,$result);

        $response = Yii::$app->response;
        $response->data = $result;
        $response->format = 'json';
        return $response;
    }

    public function actionUpdateLexArtElem($id_lex_article_element,$sub_type,$text,$lemma,$id_element) {
        $lex_article_element = LexArticleElement::findOne($id_lex_article_element);

        if($sub_type !== '') {
            $lex_article_element->id_sub_element = $sub_type;
        }else {
            $lex_article_element->id_sub_element = null;
        }
        if ($lemma != "") {
            $element = Element::findOne($id_element);
            if (strtolower($element->elementType->name) == strtolower('Lema')) {
                $lex_article_element->element = $lemma;
            }
        }else {
            $lex_article_element->element = $text;
        }

        $lex_article_element->save();

        $result = [];

        $keys = ['lex_article_element','element','sub_model'];
        array_push($result,$lex_article_element);
        array_push($result,$lex_article_element->element0);
        array_push($result, $lex_article_element->subModel);

        if($lex_article_element->subElement != null) {
            array_push($keys,'sub_element');
            array_push($result,$lex_article_element->subElement);
            array_push($keys,'sub_element_type');
            array_push($result,$lex_article_element->subElement->elementSubType->name);
        }
        $result = array_combine($keys,$result);

        $response = Yii::$app->response;
        $response->data = $result;
        $response->format = 'json';
        return $response;
    }

    public function actionGetLexArticleElement($lex_article_elements_id) {

        $lex_article_elements_id = Json::decode($lex_article_elements_id);

        $result = [];

        foreach ($lex_article_elements_id as $lex_article_element_id) {
            $lex_article_element = LexArticleElement::findOne($lex_article_element_id);

            $element = $lex_article_element->element0;
            $sub_element = $lex_article_element->subElement;
            $sub_model = $lex_article_element->subModel;

            $sub_sep = '';

            $elements = SubModelElement::find()->where(['id_sub_model' => $sub_model->id_sub_model])->orderBy('order')->all();

            $first = $elements[0];

            $last_element = Element::findOne($first->id_element);

            if ($element->id_element == $last_element->id_element){
                try{
                    $sub_model_separator = SubModelSeparator::findOne(['id_sub_model' => $sub_model->id_sub_model ]);
                    $sub_sep = Separator::findOne(['id_separator' => $sub_model_separator->id_separator]);
                }catch (\Exception $exception) {}

            }

            try{
                $element_separator = ElementSeparator::findOne(['id_sub_model' => $sub_model->id_sub_model, 'id_element' => $element->id_element]);
                $element_separator = Separator::findOne(['id_separator' => $element_separator->id_separator]);
            }catch (\Exception $exception){}

            $lex_element = [];
            $keys = ['element','element_separator','lex_article_element','sub_model'];

            array_push($lex_element,$element);
            array_push($lex_element ,$element_separator);
            array_push($lex_element, $lex_article_element);
            array_push($lex_element, $sub_model);

            if($lex_article_element->subElement != null) {
                array_push($keys,'sub_element');
                array_push($lex_element,$lex_article_element->subElement);
                array_push($keys,'sub_element_type');
                array_push($lex_element,$lex_article_element->subElement->elementSubType->name);
            }

            array_push($keys,'sub_model_separator');
            array_push($lex_element,$sub_sep);

            $element = array_combine($keys, $lex_element);

            array_push($result,$element);
        }

        $response = Yii::$app->response;
        $response->format = 'json';
        $response->data = $result;

        return $response;
    }

    public function actionGetLexArticle($id_lex_article){
        $lex_article = LexArticle::findOne($id_lex_article);

        $result = [];
        $keys = ['lex_article','lex_article_elements'];
        array_push($result,$lex_article);

        $lex_article_elements = LexArticleElement::find()->where(['id_lex_article' => $lex_article->id_lex_article])->orderBy('order')->all();

        array_push($result,$lex_article_elements);
        $result = array_combine($keys,$result);

        $response = Yii::$app->response;
        $response->data = $result;
        $response->format = 'json';
        return $response;
    }

    public function actionSaveLexArticle() {
        if (Yii::$app->request->post()){
            $id_lex_article = Yii::$app->request->post('lex_article_id');
            $id_revision_plan = Yii::$app->request->post('id_revision_plan');
            $lex_article = LexArticle::findOne($id_lex_article);
            $lex_article->id_lemma = Yii::$app->request->post('lemma_id');
            $lex_article->article = Yii::$app->request->post('article');
            $finished = Yii::$app->request->post('finished');
            $reviewed = Yii::$app->request->post('reviewed');
            $finished = $finished == 'true' ? true : false;
            $reviewed = $reviewed == 'true' ? true : false;
            $lex_article->finished = $finished;
            $lex_article->reviewed = $reviewed;
            $lex_article->save();

            return $this->redirect(['indexedition', 'id_revision_plan' => $id_revision_plan]);
        }
    }

    public function actionGetLemarioLemmas($id_lemma,$id_project) {
        $lemmas = Lemma::find()->where(['id_project' => $id_project])->andWhere(['!=','id_lemma',$id_lemma])->all();

        $result = [];

        foreach ($lemmas as $lemma) {
            array_push($result,$lemma->extracted_lemma);
        }

        $response = Yii::$app->response;
        $response->data = $result;
        $response->format = 'json';
        return $response;
    }


}