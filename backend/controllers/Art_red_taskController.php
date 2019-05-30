<?php

namespace backend\controllers;

use antkaz\vue\VueAsset;
use backend\assets\AppAsset;
use backend\models\DictionaryLink;
use backend\models\Element;
use backend\models\ElementSeparator;
use backend\models\Lemma;
use backend\models\LexArticleElement;
use backend\models\Project;
use backend\models\RedactionPlan;
use backend\models\Separator;
use backend\models\SubElement;
use backend\models\SubModel;
use backend\models\SubModelElement;
use backend\models\SubModelSeparator;
use common\models\User;
use Yii;
use backend\models\LexArticle;
use backend\models\LexArticleSearch;
use yii\db\StaleObjectException;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use yii\web\Response;
use yii\web\View;

/**
 * Art_red_taskController implements the CRUD actions for LexArticle model.
 */
class Art_red_taskController extends Controller
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
     * Lists all LexArticle models.
     * @param $id_redaction_plan
     * @return mixed
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionIndex($id_redaction_plan)
    {
        $plan = RedactionPlan::findOne($id_redaction_plan);
        $project = $plan->project;

        if (User::userCanRedaction($project->id_project)) {
            $letters = $plan->letters;

            /*$lex_articles = LexArticle::find()->all();
            foreach ($lex_articles as $article){
                if ($article->article == null || $article->article == "") {
                    $article->delete();
                }
            }*/

            $searchModel = new LexArticleSearch();
            $searchModel->letters = $letters;
            $searchModel->id_project = $project->id_project;
            //$searchModel->id_redaction_plan = $plan->id_redaction_plan;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', compact('searchModel','dataProvider','project','plan','letters'));
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Displays a single LexArticle model.
     * @param integer $id
     * @param $id_redaction_plan
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionView($id,$id_redaction_plan)
    {
        $plan = RedactionPlan::findOne($id_redaction_plan);
        $project = $plan->project;

        if (User::userCanRedaction($project->id_project)) {
            $model = LexArticle::findOne($id);

            return $this->render('view', compact('project','plan','model'));
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Creates a new LexArticle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $id_redaction_plan
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionCreate($id_redaction_plan)
    {
        $plan = RedactionPlan::findOne($id_redaction_plan);
        $project = $plan->project;
        if (User::userCanRedaction($project->id_project)) {
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/redaction.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction/axios.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction/lodash.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction/lex_article_element.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction/modal-lex-article-element.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction/autocomplete.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction/app.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);

            return $this->render('create', compact('project','plan'));
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    public function actionLemmas($id_letter,$id_redaction_plan) {
        $plan = RedactionPlan::findOne($id_redaction_plan);
        $project = $plan->project;

        $lemmas = Lemma::find()->where(['id_project' => $project->id_project, 'id_letter' => $id_letter, 'lemario' => true, 'agree' => true])->orderBy('extracted_lemma')->all();

        $lemma_list = [];

        foreach ($lemmas as $lemma) {
            $lex_article = LexArticle::findOne(['id_lemma' => $lemma->id_lemma]);
            if ($lex_article == null) {
                array_push($lemma_list,$lemma);
            }
        }

        $response = Yii::$app->response;
        $response->data = $lemma_list;
        $response->format = 'json';

        return $response;
    }

    /**
     * Finds the LexArticle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LexArticle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LexArticle::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPlans($id_project) {
        if (User::userCanRedaction($id_project)) {
            $project = Project::findOne($id_project);
            $user_id = Yii::$app->user->id;
            $plans = RedactionPlan::find()->where(['id_project' => $id_project, 'id_user' => $user_id])->all();

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            return $this->render('plans', compact('plans', 'project'));
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Updates an existing LexArticle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param $id_redaction_plan
     * @return mixed
     * @throws NotAcceptableHttpException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$id_redaction_plan)
    {
        $lex_article = $this->findModel($id);
        $plan = RedactionPlan::findOne($id_redaction_plan);
        $project = $plan->project;
        if (User::userCanRedaction($project->id_project)) {
            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/redaction.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction_update/axios.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction_update/lodash.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction_update/lex-article-element.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction_update/modal-lex-article-element.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction/autocomplete.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/redaction_update/app.js', ['depends' => [VueAsset::className()], 'position' => View::POS_END]);

            return $this->render('update', compact('lex_article', 'project', 'plan'));
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Deletes an existing LexArticle model.
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

        if (User::userCanRedaction($model->lemma->id_project)) {

            if ($model->delete())
                return "Ok";
            else
                return "Error";
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    public function actionViewDelete($id,$id_redaction_plan) {

        try {
            $model = $this->findModel($id);
        } catch (NotFoundHttpException $e) {
        }
        if (User::userCanRedaction($model->id_project)) {

            foreach ($model->lexArticleElements as $element) {
                try {
                    $element->delete();
                } catch (StaleObjectException $e) {
                } catch (\Throwable $e) {
                }
            }

            try {
                $model->delete();
            } catch (StaleObjectException $e) {
            } catch (\Throwable $e) {
            }

            return $this->redirect(['index', 'id_redaction_plan' => $id_redaction_plan]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    public function actionLetters($id_redaction_plan) {
        $plan = RedactionPlan::findOne($id_redaction_plan);

        $letters = $plan->letters;

        $response = Yii::$app->response;
        $response->data = $letters;
        $response->format = 'json';

        return $response;
    }

    public function actionArea($id_lemma,$id_redaction_plan,$id_lex_article) {

        $lemma = Lemma::findOne($id_lemma);

        $plan = RedactionPlan::findOne($id_redaction_plan);

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

        array_push($keys, 'sub_models_plan');
        array_push($ordered, $plan->submodels);

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

    public function actionGetConfirmation() {
        return true;
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

        $lex_article_element->save(false);

        $result = [];

        $keys = ['lex_article_element','element'];
        array_push($result,$lex_article_element);
        array_push($result,$lex_article_element->element0);


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
        $lex_article = $this->findModel($id_lex_article);

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
            $id_redaction_plan = Yii::$app->request->post('id_redaction_plan');
            $lex_article = LexArticle::findOne($id_lex_article);
            $lex_article->id_lemma = Yii::$app->request->post('lemma_id');
            $lex_article->article = Yii::$app->request->post('article');
            $finished = Yii::$app->request->post('finished');
            $finished = $finished == 'true' ? true : false;
            $lex_article->finished = $finished;
            $lex_article->reviewed = false;

            $lex_article->save();

            return $this->redirect(['index', 'id_redaction_plan' => $id_redaction_plan]);
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

    public function actionFinish($id_redaction_plan) {

        $red_plan = RedactionPlan::findOne($id_redaction_plan);

        $red_plan->finished = true;
        $red_plan->save(false);

        $project = Project::findOne(['id_project' => $red_plan->id_project]);

        $user = Yii::$app->user;
        $plans = RedactionPlan::find()->where(['id_project' => $project->id_project ,'id_user' => $user->id ,'finished' => false])->all();

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
    }
}
