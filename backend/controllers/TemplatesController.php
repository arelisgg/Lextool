<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Element;
use backend\models\ElementSeparator;

use backend\models\Project;
use backend\models\Separator;
use backend\models\SubModelElement;
use backend\models\SubModelSeparator;
use backend\models\TemplateElement;
use backend\models\Templates;
use backend\models\TemplateSearch;
use backend\models\TemplateSubModel;
use backend\models\TemplateSeparator;
use common\models\User;
use Throwable;
use Yii;
use backend\models\SubModel;
use backend\models\SubModelSearch;
use yii\db\JsonExpression;
use yii\helpers\BaseArrayHelper;
use yii\helpers\Json;
use yii\rest\Serializer;
use yii\web\Controller;
use yii\web\JsonParser;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\View;

/**
 * TemplatesController implements the CRUD actions for Templates model.
 */
class TemplatesController extends Controller
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
     * Lists all Templates models.
     * @param $id_project
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $project = Project::findOne($id_project);

            $searchModel = new TemplateSearch();
            $searchModel->id_project = $id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'project' => $project
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }
    /**
     * Creates a new Templates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $id_project
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    /* public function actionCreate($id_project)
     {
         $model= new Templates();
         $project = Project::findOne($id_project);

         if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){

             $submodels = SubModel::find()->where(['id_project' => $id_project])->all();
             $separators = Separator::find()->where(['id_project'=> $id_project,'scope' => ["Componente"]])->all();

             if (Yii::$app->request->post() ) {

                 $posts = Yii::$app->request->post();

                 $posts = array_reverse($posts);

                 $order = count($posts) - 1;

                 $id_submodel = "";

                 foreach ($posts as $key => $value) {
                     $key_val = explode('-',$key);

                     if ($key_val[0] == "submodel") {
                         $submodel = SubModel::findOne($value);
                         $submodel->order = $order;
                         $submodel->id_template = $model->id_template;
                         $submodel->save();
                         $id_submodel = $submodel->id_sub_model;

                     } else if ($key_val[0] == "separator") {
                         $submodel_separator = new SubModelSeparator();
                         $submodel_separator->id_template = $model->id_template;
                         $submodel_separator->id_separator = $value;
                         $submodel_separator->id_sub_model = $id_submodel;
                         $submodel_separator->order = $order;
                         $submodel_separator->save();
                     }
                     $order--;
                 }
                 $model->save();
                 return $this->redirect(['index', 'id_project' => $id_project]);
             }

             //iCheck
             $this->view->registerCssFile(Yii::$app->homeUrl . 'js/iCheck/square/blue.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
             $this->view->registerJsFile(Yii::$app->homeUrl . 'js/iCheck/icheck.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
             $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_icheck', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

             $this->view->registerCssFile(Yii::$app->homeUrl . 'css/general_model.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
             $this->view->registerJsFile(Yii::$app->homeUrl . 'js/general_model.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

             //Sortable
             $this->view->registerJsFile(Yii::$app->homeUrl . 'js/sortable/Sortable.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
             $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_sortable_general_model.js', ['depends' => [AppAsset::className()], 'position' => View::POS_END]);

             return $this->render('create', array( 'model'=>$model ,'project'=>$project, 'submodels'=> $submodels,'separators'=>$separators ));
         } else
             throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

     }*/
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new Templates();
            $project = Project::findOne($id_project);

            $submodels = SubModel::find()->where(['id_project' => $id_project])->all();
            $separators = Separator::find()->where(['id_project' => $id_project, 'scope' => 'Componente'])->orderBy('id_separator')->all();
            $elements = Element::find()->where(['id_project' => $id_project])->all();

            if ($model->load(Yii::$app->request->post())) {

                $model->save();

                $posts = Yii::$app->request->post();


                $j = 1;
                $order = 0;
                foreach ($posts as $key => $value) {

                    $submodel_id = "";
                    $element_id ="";

                    if (!is_array($value)){
                        $submodel_id = strval($value);
                        $element_id =strval($value);
                    }

                    $separator_id = strval($j);

                    if ($key == "submodel-".$submodel_id){
                        $submodel_n = new SubModel();
                        $template_submodel = new TemplateSubModel();

                        $template_submodel->id_template = $model->id_template;
                        $template_submodel->id_sub_model = $value;
                        $template_submodel->order = $order;
                        $submodel_n = SubModel::findOne($value);
                        $submodel_n->order =$order;
                        $submodel_n->id_template = $model->id_template;
                        $submodel_n-> save();
                        $template_submodel->save();

                        $order++;
                    }
                    if ($key == "element-".$element_id){

                        $template_element = new TemplateElement();

                        $template_element->id_template = $model->id_template;
                        $template_element->id_element = $value;
                        $template_element->order = $order;

                        $template_element->save();

                        $order++;
                    }

                    if ($key == "separator-".$separator_id) {

                        $template_separator= new TemplateSeparator();

                        $template_separator->id_template = $model->id_template;
                        $template_separator->id_separator = $value;
                        $template_separator->order = $order;

                        $submodel_id = strval($template_submodel->id_sub_model);
                        $submodel = Yii::$app->request->post('submodel-'.$submodel_id);
                        $template_separator->id_sub_model = $submodel;

                        $template_separator->save();

                        $j++;
                        $order++;
                    }
                }

                return $this->redirect(['index', 'id_project' => $id_project]);
            }

            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/iCheck/square/blue.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/iCheck/icheck.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_icheck', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/general_model.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/general_model.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/submodel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/sub_model.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            //Sortable
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/sortable/Sortable.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_sortable_general_model.js', ['depends' => [AppAsset::className()], 'position' => View::POS_END]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_sortable.js', ['depends' => [AppAsset::className()], 'position' => View::POS_END]);

            return $this->render('create', array('model'=>$model ,'project'=>$project, 'submodels'=> $submodels,'separators'=>$separators));
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }
    /**
     * Updates an existing Templates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id_project
     * @return mixed
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $project = Project::findOne($id_project);
            $submodels = SubModel::find()->where(['id_project' => $id_project])->all();
            $separators = Separator::find()->where(['id_project'=> $id_project,'scope' => ["Componente"]])->all();

            $general_model_submodels = SubModel::find()->where(['id_project' => $id_project])->andWhere(['>','order',0])->orderBy('order')->all();
            $general_model = [];

            foreach ($general_model_submodels as $submodel){
                $submodel_separators = SubModelSeparator::find()->where(['id_sub_model' => $submodel->id_sub_model])->orderBy('order')->all();
                foreach ($submodel_separators as $separator) {
                    $sep = Separator::findOne($separator->id_separator);
                    array_push($general_model,$sep);
                }
                array_push($general_model,$submodel);
            }

            if (Yii::$app->request->post()) {

                $id_project = Yii::$app->request->post('id_project');

                $general_model_sub = SubModel::find()->where(['id_project' => $id_project])->andWhere(['>','order',0])->orderBy('order')->all();
                foreach ($general_model_sub as $submodel) {
                    $submodel->order = null;
                    $submodel->save();

                    $submodel_separators = SubModelSeparator::find()->where(['id_sub_model' => $submodel->id_sub_model])->orderBy('order')->all();
                    foreach ($submodel_separators as $separator) {
                        $separator->delete();
                    }
                }

                $posts = Yii::$app->request->post();

                $posts = array_reverse($posts);

                $order = count($posts) - 2;

                foreach ($posts as $key => $value) {

                    $key_val = explode('-',$key);

                    if ($key_val[0] == "submodel") {
                        $submodel = SubModel::findOne($value);
                        $submodel->order = $order;
                        $submodel->save();
                    } else if ($key_val[0] == "separator") {
                        $submodel_separator = new SubModelSeparator();
                        $submodel_separator->id_separator = $value;
                        $submodel_separator->id_sub_model = $submodel->id_sub_model;
                        $submodel_separator->order = $order;
                        $submodel_separator->save();
                    }
                    $order--;
                }

                               $lex_articles = LexArticle::find()
                    ->innerJoin('lemma','lemma.id_lemma = lex_article.id_lemma')
                    ->where(['id_project' => $id_project])->all();
                foreach ($lex_articles as $lex_article) {
                    $lex_article->finished = false;
                    $lex_article->save();
                }


                return $this->redirect(['index', 'id_project' => $id_project]);
            }

            //iCheck
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/iCheck/square/blue.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/iCheck/icheck.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_icheck', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/general_model.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/general_model.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            //Sortable
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/sortable/Sortable.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_sortable_general_model.js', ['depends' => [AppAsset::className()], 'position' => View::POS_END]);


            return $this->render('update', compact('project','general_model','separators','submodels'));
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Deletes an existing Templates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id_project
     * @return mixed
     * @throws NotAcceptableHttpException
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){

            $submodels = SubModel::find()->where(['id_project'=>$id_project])->all();

            $stop = false;

            for ($i = 0; $i < count($submodels) && !$stop; $i++) {
                if (count($submodels[$i]->redactionPlans) > 0){
                    $stop = true;
                }
            }

            if (!$stop) {
                foreach ($submodels as $submodel) {
                    $submodel->order = null;
                    $submodel->save(false);

                    SubModelSeparator::deleteAll(['id_sub_model' => $submodel->id_sub_model]);
                }

                return $this->redirect(['index', 'id_project' => $id_project]);
            }else {
                return "Error";
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Finds the SubModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubModel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }

    public function actionDetails($id) {

        $submodel_elements = SubModelElement::find()->where(['id_sub_model'=> $id])->all();
        $elements = [];
        $order = [];

        foreach ($submodel_elements as $element){
            $el = Element::findOne($element->id_element);
            array_push($order,$element->order);
            array_push($elements,$el->elementType->name);

            $separators = ElementSeparator::find()->where(['id_element'=> $el->id_element, 'id_sub_model' => $id])->all();
            foreach ($separators as $separator) {
                if ($separator->id_separator != -1){
                    $sep = Separator::findOne($separator->id_separator);
                    array_push($order,$separator->order);
                    array_push($elements,$sep->representation);
                }
            }
        }

        $ordered = array_combine($order,$elements);
        $ordered = array_merge_recursive($ordered);

        return $this->renderAjax('details', compact('ordered'));
    }

    public function actionList($id) {
        $submodels = SubModel::find()->where(['id_project' => $id])->andWhere(['>','order', 0])->orderBy('order')->all();

        $ordered = [];

        foreach ($submodels as $submodel) {
            $submodel_separator = SubModelSeparator::findOne(['id_sub_model' => $submodel->id_sub_model]);
            try{
                $sub_sep = Separator::findOne(['id_separator' => $submodel_separator->id_separator]);
                array_push($ordered,$sub_sep);
            }catch (\Exception $exception){}

            $submodel_element = SubModelElement::findAll(['id_sub_model' => $submodel->id_sub_model]);
            foreach ($submodel_element as $element) {
                $element_arr = [];
                $sub_element_arr = [];

                $el = Element::findOne(['id_element' => $element->id_element]);

                array_push($element_arr,$el->elementType->name);

                foreach ($el->subElements as $sub_element) {
                    array_push($sub_element_arr, $sub_element->elementSubType->name);
                }

                array_push($element_arr,$sub_element_arr);

                array_push($ordered,$element_arr);


                try{
                    $sep = ElementSeparator::findOne(['id_element'=> $el->id_element, 'id_sub_model' => $submodel->id_sub_model]);
                    $separator = Separator::findOne(['id_separator'=>$sep->id_separator]);

                    array_push($ordered, $separator);
                }catch (\Exception $exception){}

            }
        }

        $response = Yii::$app->response;

        $response->data = $ordered;

        $response->format = "json";

        return  $response;
    }

    public function actionVerify($id_project)
    {
        $result = [];
        $result_keys = ['can_delete','error_list'];

        $project = Project::findOne($id_project);

        $redaction_plans = $project->redactionPlans;
        $revision_plans = $project->revisionPlans;
        $lemmas = $project->lemmas;

        $redacted_lemmas = 0;

        foreach ($lemmas as $lemma) {
            if ($lemma->lexArticle != null) {
                $redacted_lemmas++;
            }
        }

        $error_list = [];

        if (count($redaction_plans) > 0) {
            array_push($error_list,'Hay planes de redacción asociados al modelo de artículo.');
        }
        if (count($revision_plans) > 0){
            array_push($error_list,'Hay planes de revisión asociados al modelo de artículo.');
        }
        if ($redacted_lemmas > 0){
            array_push($error_list,'Hay lemas redactados asociados al modelo de artículo.');
        }

        if (count($error_list) > 0) {
            array_push($result,false);
        }else {
            array_push($result,true);
        }

        array_push($result,$error_list);

        $result = array_combine($result_keys,$result);

        $response = Yii::$app->response;
        $response->data = $result;
        $response->format = 'json';
        return $response;
    }
}
