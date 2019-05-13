<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Element;
use backend\models\ElementSeparator;

use backend\models\Project;
use backend\models\Separator;
use backend\models\SubModelElement;
use backend\models\SubModelSeparator;
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
 * Sub_modelController implements the CRUD actions for SubModel model.
 */
class Sub_modelController extends Controller
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

    /**
     * Lists all SubModel models.
     * @return mixed
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $project = Project::findOne($id_project);

            $searchModel = new SubModelSearch();
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
     * Displays a single SubModel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws NotAcceptableHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $project = $model->project;
        if(User::userCanProjectAndRol($project->id_project, "Jefe de Proyecto")){
            //d3-tree
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/d3-mitch-tree/dist/css/d3-mitch-tree.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/d3-mitch-tree/dist/css/d3-mitch-tree-theme-default.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/d3-mitch-tree/dist/js/d3-mitch-tree.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('view', compact('model','project'));
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');



    }

    /**
     * Creates a new SubModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new SubModel();

            $project = Project::findOne($id_project);

            $elements = Element::find()->where(['id_project' => $id_project])->orderBy('id_element_type')->all();
            $separators = Separator::find()->where(['id_project' => $id_project, 'scope' => 'Elemento'])->orderBy('id_separator')->all();

            if ($model->load(Yii::$app->request->post())) {

                $repeat = Yii::$app->request->post('repeat');
                $required = Yii::$app->request->post('required');

                if ($repeat == "on") {
                    $model->repeat = true;
                }elseif ($repeat == "") {
                    $model->repeat = false;
                }

                if ($required == "on") {
                    $model->required = true;
                }elseif ($required == "") {
                    $model->required = false;
                }

                $model->save();

                $posts = Yii::$app->request->post();


                $j = 1;
                $order = 0;
                foreach ($posts as $key => $value) {

                    $element_id = "";

                    if (!is_array($value)){
                        $element_id = strval($value);
                    }

                    $separator_id = strval($j);

                    if ($key == "element-".$element_id){

                        $submodel_element = new SubModelElement();

                        $submodel_element->id_sub_model = $model->id_sub_model;
                        $submodel_element->id_element = $value;
                        $submodel_element->order = $order;

                        $submodel_element->save();


                        $order++;
                    }
                    if ($key == "separator-".$separator_id) {

                        $element_separator = new ElementSeparator();
                        $element_separator->id_sub_model = $model->id_sub_model;
                        $element_separator->id_separator = $value;
                        $element_separator->order = $order;

                        $element_id = strval($submodel_element->id_element);
                        $element = Yii::$app->request->post('element-'.$element_id);
                        $element_separator->id_element = $element;

                        $element_separator->save();

                        $j++;
                        $order++;
                    }
                }

                return $this->redirect(['view', 'id' => $model->id_sub_model]);
            }

            //iCheck
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/iCheck/square/blue.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/iCheck/icheck.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_icheck', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            //Sortable
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/sortable/Sortable.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_sortable.js', ['depends' => [AppAsset::className()], 'position' => View::POS_END]);

            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/submodel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/sub_model.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('create', compact('model','elements','project','separators'));
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Updates an existing SubModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $project = $model->project;
        if(User::userCanProjectAndRol($project->id_project, "Jefe de Proyecto")){
            $elements = Element::find()->where(['id_project' => $project->id_project])->orderBy('id_element_type')->all();
            $separators = Separator::find()->where(['id_project' =>  $project->id_project, 'scope' => 'Elemento'])->orderBy('id_separator')->all();

            $submodel_elements =  $model->subModelElements;

            $submodel = [];

            foreach ($submodel_elements as $element) {
                $el = Element::findOne($element->id_element);
                array_push($submodel,$el);

                $element_separators = ElementSeparator::find()->where(['id_element'=> $el->id_element, 'id_sub_model' => $id])->all();
                foreach ($element_separators as $separator) {
                    if ($separator->id_separator != -1){
                        $sep = Separator::findOne($separator->id_separator);
                        array_push($submodel,$sep);
                    }
                }
            }

            $ordered = array_merge_recursive($submodel);

            if ($model->load(Yii::$app->request->post())) {

                $model->save();

                $submodel_elements = SubModelElement::find()->where(['id_sub_model' => $model->id_sub_model])->all();
                foreach ($submodel_elements as $element) {
                    $element->delete();
                }
                $element_separators = ElementSeparator::find()->where(['id_sub_model' => $model->id_sub_model])->all();
                foreach ($element_separators as $element_separator) {
                    $element_separator->delete();
                }

                $posts = Yii::$app->request->post();

                $j = 1;
                $order = 0;
                foreach ($posts as $key => $value) {

                    $element_id = "";

                    if (!is_array($value)) {
                        $element_id = strval($value);
                    }

                    $separator_id = strval($j);

                    if ($key == "element-".$element_id){

                        $submodel_element = new SubModelElement();

                        $submodel_element->id_sub_model = $model->id_sub_model;
                        $submodel_element->id_element = $value;
                        $submodel_element->order = $order;

                        $submodel_element->save();

                        $order++;
                    }
                    if ($key == "separator-".$separator_id) {

                        $element_separator = new ElementSeparator();
                        $element_separator->id_sub_model = $model->id_sub_model;
                        $element_separator->id_separator = $value;
                        $element_separator->order = $order;

                        $element_id = strval($submodel_element->id_element);
                        $element = Yii::$app->request->post('element-'.$element_id);
                        $element_separator->id_element = $element;
                        $element_separator->save();

                        $j++;
                        $order++;
                    }
                }

                return $this->redirect(['view', 'id' => $model->id_sub_model]);
            }

            //iCheck
            $this->view->registerCssFile(Yii::$app->homeUrl . 'js/iCheck/square/blue.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/iCheck/icheck.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_icheck', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            //Sortable
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/sortable/Sortable.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init_sortable.js', ['depends' => [AppAsset::className()], 'position' => View::POS_END]);

            $this->view->registerCssFile(Yii::$app->homeUrl . 'css/submodel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/sub_model.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            return $this->render('update', compact('model','project','elements','separators','ordered'));
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');


    }

   /**
     * Deletes an existing SubModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(User::userCanProjectAndRol($model->id_project, "Jefe de Proyecto")){
            if ($model->order == null) {
                $model->delete();
                return "Ok";
            }
            else {
                return "Error";
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');


    }

    /**
     * Deletes an existing SubModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionViewDelete($id)
    {
        $model = $this->findModel($id);
        if(User::userCanProjectAndRol($model->id_project, "Jefe de Proyecto")){
            if ($model->order == null) {
                $id_project = $model->id_project;
                $model->delete();
                return $this->redirect(['index', 'id_project' => $id_project]);
            }
            else {
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDetails($id){
        $element = Element::findOne($id);
        return $this->renderAjax('details', compact('element'));
    }


    public function actionList($id) {
        $submodel_elements = SubModelElement::find()->where(['id_sub_model' => $id])->all();

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
        $ordered = array_reverse($ordered);

        $response = Yii::$app->response;

        $response->data = $ordered;

        $response->format = "json";

        return  $response;
    }
}
