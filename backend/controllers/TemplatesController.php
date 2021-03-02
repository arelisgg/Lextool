<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Element;
use backend\models\ElementSeparator;

use backend\models\LemmaExtPlanTemplate;
use backend\models\Project;
use backend\models\Separator;
use backend\models\SubModelElement;
use backend\models\SubModelSeparator;
use backend\models\TemplateElement;
use backend\models\Templates;
use backend\models\TemplateSearch;
use backend\models\TemplateSubModel;
use backend\models\TemplateSeparator;
use backend\models\TemplateType;
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
use yii\web\UploadedFile;

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
                    'delete' => ['post'],
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
        if (User::userCanProjectAndRol($id_project, "Jefe de Proyecto")) {
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

    public function actionCreate($id_project, $id)
    {
        if (User::userCanProjectAndRol($id_project, "Jefe de Proyecto")) {
            $t = Templates::findOne($id);
            $project = Project::findOne($id_project);
            $submodels = SubModel::find()->where(['id_project' => $id_project])->all();
            $separators = Separator::find()->where(['id_project' => $id_project, 'scope' => 'Componente'])->orderBy('id_separator')->all();
            $elements = Element::find()->where(['id_project' => $id_project])->all();
            $type = $t->id_template_type;
            $model = $t;


            if ($posts = Yii::$app->request->post()) {

                $posts = Yii::$app->request->post();
                $j = 1;
                $order = 1;
                $n=null;
                foreach ($posts as $key => $value) {
                    $submodel_id = "";
                    $element_id = "";
                    if (!is_array($value)) {
                        $submodel_id = strval($value);
                        $element_id = strval($value);
                    }
                    $separator_id = strval($j);
                    if ($key == "submodel-" . $submodel_id) {
                        $submodel_n = new SubModel();
                        $template_submodel = new TemplateSubModel();
                        $template_submodel->id_template = $model->id_template;
                        $template_submodel->id_sub_model = $value;
                        $template_submodel->order = $order;
                        $submodel_n = SubModel::findOne($value);
                        $submodel_n->order = $order;
                        $submodel_n->used= true;
                        $submodel_n->id_template = $model->id_template;
                       if($submodel_n->save()){ $n++;}
                        if($template_submodel->save()){$n++;}
                        $order++;
                    }
                    if ($key == "element-" . $element_id) {
                        $element_n = new Element();
                        $template_element = new TemplateElement();
                        $template_element->id_template = $model->id_template;
                        $template_element->id_element = $value;
                        $template_element->order = $order;
                        $element_n = Element::findOne($value);
                        $element_n->id_template = $model->id_template;
                        $element_n->used=true;
                        if($element_n->save()){$n++;}
                        if($template_element->save()){$n++;}
                        $order++;
                    }
                    if ($key == "separator-" . $separator_id) {
                        $template_separator = new TemplateSeparator();
                        $template_separator->id_template = $model->id_template;
                        $template_separator->id_separator = $value;
                        $template_separator->order = $order;
                        $submodel_id = strval($template_submodel->id_sub_model);
                        $submodel = Yii::$app->request->post('submodel-' . $submodel_id);
                        $template_separator->id_sub_model = $submodel;
                        $template_separator->save();
                        $j++;
                        $order++;
                    }

                }
                if ($n==0){
                    Yii::$app->session->setFlash('error','La Plantilla debe contener al menos un elemento.');
                    $model->delete();
                    return $this->redirect(['datos','id_project' => $id_project]);
                }

                return $this->redirect(['view', 'id' => $model->id_template]);
            }
            $type = $model->id_template_type;
            $t = TemplateType::findOne($type);
            $stage = $t->stage;

            if ($stage == 'Extraccion') {
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

            } else {
                if ($stage == 'Redaccion'):
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
                endif;
            }

            return $this->render('create', array('model' => $model, 'project' => $project, 'submodels' => $submodels, 'separators' => $separators, 'elements' => $elements));
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    public function actionDatos($id_project)
    {
        if (User::userCanProjectAndRol($id_project, "Jefe de Proyecto")) {
            $model = new Templates();
            $project = Project::findOne($id_project);



            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $file = UploadedFile::getInstance($model, "ref_file");
                if (!empty($file)) {
                    $address = $model->id_template . ' - ' . $model->name . ' (Ref_file).' . $file->extension;
                    $file->saveAs('uploads/templates/ref_file/' . $address);
                    $model->ref_file = $address;
                }
                $model->save();
                return $this->redirect(['create', 'id_project' => $id_project, 'id' => $model->id_template]);
            }

            $this->view->registerCssFile(Yii::$app->homeUrl.'css/project.css',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl.'js/project.js',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);

            return $this->render('datos', array('model' => $model, 'project' => $project));
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Updates an existing Templates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function actionUpdate($id)
    {
        $model = new Templates();
        $model = Templates::findOne(['id_template' => $id]);
        $id_project = $model->id_project;
        $project= Project::findOne(['id_project'=>$id_project]);
        $n=null;

        if (User::userCanProjectAndRol($id_project, "Jefe de Proyecto")) {

            $submodels = SubModel::find()->where(['id_project' => $id_project])->all();
            $separators = Separator::find()->where(['id_project' => $id_project, 'scope' => 'Componente'])->orderBy('id_separator')->all();
            $elements = Element::find()->where(['id_project' => $id_project])->all();

            $template_elements = $model->templateElements;
            $element = [];

            $template_submodels = $model->templateSubModels;
            $submodel = [];


            if (empty($template_elements)) {

                foreach ($template_submodels as $tsm) {
                    $ts = SubModel::findOne($tsm->id_sub_model);
                    array_push($submodel, $ts);

                    $template_separators = TemplateSeparator::find()->where(['id_sub_model' => $ts->id_sub_model, 'id_template' => $id])->all();
                    foreach ($template_separators as $separator) {
                        if ($separator->id_separator != -1) {
                            $sep = Separator::findOne($separator->id_separator);
                            array_push($submodel, $sep);
                        }
                    }
                }
                $orderer = array_merge_recursive($submodel);
            } else {
                foreach ($template_elements as $elem) {
                    $el = Element::findOne($elem->id_element);
                    array_push($element, $el);
                }
                $orderer = array_merge_recursive($element);
            }

            if ($posts = Yii::$app->request->post()) {


                $template_submodels = TemplateSubModel::find()->where(['id_template' => $model->id_template])->all();
                $template_elements = TemplateElement::find()->where(['id_template' => $model->id_template])->all();
                foreach ($template_elements as $element) {
                    $e=Element::find()->where(['id_element'=>$element->id_element, 'id_template'=>$id])->one();
                    if (!empty($e)){
                        $e->id_template= null;
                        $e->used=false;
                        $e->save();
                    }
                    $element->delete();
                }
                foreach ($template_submodels as $subm) {
                    $sm=SubModel::find()->where(['id_sub_model'=>$subm->id_sub_model, 'id_template'=>$id])->one();
                    if (!empty($sm)) {
                        $sm->id_template = null;
                        $sm->used = false;
                        $sm->save();
                    }
                    $subm->delete();
                }
                $template_separators = TemplateSeparator::find()->where(['id_template' => $model->id_template])->all();
                foreach ($template_separators as $t_separator) {
                    $t_separator->delete();
                }


                $posts = Yii::$app->request->post();
                $j = 1;
                $order = 1;
                foreach ($posts as $key => $value) {
                    $submodel_id = "";
                    $element_id = "";
                    if (!is_array($value)) {
                        $submodel_id = strval($value);
                        $element_id = strval($value);
                    }
                    $separator_id = strval($j);
                    if ($key == "submodel-" . $submodel_id) {
                        $submodel_n = new SubModel();
                        $template_submodel = new TemplateSubModel();
                        $template_submodel->id_template = $model->id_template;
                        $template_submodel->id_sub_model = $value;
                        $template_submodel->order = $order;
                        $submodel_n = SubModel::findOne($value);
                        $submodel_n->order = $order;
                        $submodel_n->used= true;
                        $submodel_n->id_template = $model->id_template;
                        if($submodel_n->save()){ $n++;}
                        if($template_submodel->save()){$n++;}
                        $order++;

                    }
                    if ($key == "element-" . $element_id) {
                        $element_n = new Element();
                        $template_element = new TemplateElement();
                        $template_element->id_template = $model->id_template;
                        $template_element->id_element = $value;
                        $template_element->order = $order;
                        $element_n = Element::findOne($value);
                        $element_n->id_template = $model->id_template;
                        $element_n->used=true;
                        if($element_n->save()){ $n++;}
                        if($template_element->save()){$n++;}
                        $order++;
                    }
                    if ($key == "separator-" . $separator_id) {
                        $template_separator = new TemplateSeparator();
                        $template_separator->id_template = $model->id_template;
                        $template_separator->id_separator = $value;
                        $template_separator->order = $order;
                        $submodel_id = strval($template_submodel->id_sub_model);
                        $submodel = Yii::$app->request->post('submodel-' . $submodel_id);
                        $template_separator->id_sub_model = $submodel;
                        $template_separator->save();
                        $j++;
                        $order++;
                    }
                }

                if ($n==0){
                    Yii::$app->session->setFlash('error','La Plantilla debe contener al menos un elemento.');
                   return $this->redirect(['updatedatos', 'id_template'=> $model->id_template]);
                }

                return $this->redirect(['view', 'id' => $id]);
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


            return $this->render('update', array('model' => $model, 'project' => $project, 'submodels' => $submodels, 'separators' => $separators, 'elements' => $elements, 'orderer' => $orderer));
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');


    }

    public function actionUpdatedatos($id_template)

    {   $model = Templates::find()->where(['id_template'=> $id_template])->one();
        $id_project = $model->id_project ;

        if (User::userCanProjectAndRol($id_project, "Jefe de Proyecto")) {


            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $file = UploadedFile::getInstance($model, "ref_file");
                if (!empty($file)) {
                    $address = $model->id_project.' - '. $model->name .' (Ref_file).'.$file->extension;
                    $file->saveAs('uploads/templates/ref_file/' . $address);
                    $model->ref_file = $address;
                } else
                    $model->ref_file = $model->oldAttributes['ref_file'];

                $model->save(false);

                return $this->redirect(['update', 'id_project' => $id_project, 'id' => $model->id_template]);
            }
            $this->view->registerCssFile(Yii::$app->homeUrl.'css/project.css',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);
            $this->view->registerJsFile(Yii::$app->homeUrl.'js/project.js',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);

            return $this->render('updatedatos', array('id_template' => $id_template, 'model'=>$model));
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
    public function actionDelete($id)
    {
        $model = new Templates();
        $model= Templates::find()->where(['id_template'=>$id])->one();
        $project = $model->project;
        $id_project=$project->id_project;
        $type= $model->id_template_type;
        $t= TemplateType::find()->where(['id_template_type'=>$type])->one();
        $stage=$t->stage;
        $tep= LemmaExtPlanTemplate::find()->where(['id_template' => $model->id_template])->all();

        if(User::userCanProjectAndRol($project->id_project, "Jefe de Proyecto")){
            if(!empty($tep)){
                Yii::$app->session->setFlash('error','La Plantilla no puede eliminarse porque tiene planes de extracción asociados.');
                return $this->redirect(['index','id_project' => $id_project]);
            }
            if ($stage=="Extraccion"):
                $template_elements = TemplateElement::find()->where(['id_template' => $model->id_template])->all();

                foreach ($template_elements as $te){
                    $e=Element::find()->where(['id_element'=>$te->id_element, 'id_template'=>$id])->one();
                    if (!empty($e)){
                        $e->id_template= null;
                        $e->used=false;
                        $e->save();
                    }

                    $te->delete();
                }
            endif;

            if ($stage=="Redaccion"):
                $template_submodels= TemplateSubModel::find()->where(['id_template' => $model->id_template])->all();
                $template_separators = TemplateSeparator::find()->where([ 'id_template' => $model->id_template])->all();

            foreach ($template_submodels as $tsm){
                $sm=SubModel::find()->where(['id_sub_model'=>$tsm->id_sub_model, 'id_template'=>$id])->one();
                if (!empty($sm)) {
                    $sm->id_template = null;
                    $sm->used = false;
                    $sm->save();
                }
                $tsm->delete();
            }

            foreach ($template_separators as $ts){
                $ts->delete();
            }
            endif;

            $model->delete();

            return $this->redirect(['index', 'id_project' => $id_project]);
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

    public function actionView($id) {
        $model = new Templates();
        $model= Templates::findOne(['id_template'=>$id]);
        $project = $model->project;

        $submodels = SubModel::find()->where(['id_project' => $project->id_project])->all();
        $separators = Separator::find()->where(['id_project' => $project->id_project, 'scope' => 'Componente'])->orderBy('id_separator')->all();
        $elements = Element::find()->where(['id_project' => $project->id_project])->all();

        $template_elements =  $model->templateElements;
        $element=[];
        $template_submodels =  $model->templateSubModels;
        $submodel = [];


        if (empty($template_elements)) {
            $template_submodels = TemplateSubModel::find()->where(['id_template' => $id])->andWhere(['>','order',0])->orderBy('order')->all();

            foreach ($template_submodels as $tsm){
                $sm=SubModel::findOne($tsm->id_sub_model);
                array_push($submodel,$sm);
                $template_separators = TemplateSeparator::find()->where(['id_template' => $id])->andWhere(['id_sub_model'=>$tsm->id_sub_model])->orderBy('order')->all();
                foreach ($template_separators as $separator) {
                    if ($separator->id_separator != -1){
                        $sep = Separator::findOne($separator->id_separator);
                        array_push($submodel,$sep);
                    }
                }
            }
            $orderer = array_merge_recursive($submodel);
        }else{
            $template_elements = TemplateElement::find()->where(['id_template' => $id])->andWhere(['>','order',0])->orderBy('order')->all();

            foreach ($template_elements as $elem) {
                $el = Element::findOne($elem->id_element);
                array_push($element,$el);
            }
            $orderer = array_merge_recursive($element);
        }
        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/general_model_view.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/general_model_details.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/general_model_delete.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->render('view', compact('orderer','model','project'));
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

    public function actionVerify($id)
    {
        $result = [];
        $result_keys = ['can_delete','error_list'];

        $model = new Templates();
        $model= Templates::findOne(['id_template'=>$id]);
        $project = $model->project;

        $templates=Templates::find()->where(['id_template'=>$id]);
        $bool=false;
        $lept=LemmaExtPlanTemplate::find()->all();

        foreach ($lept as $t){
            if ($t->id_template==$templates->id_template){
              $bool=true;
            }
        }

        $error_list = [];

        if ($bool) {
            array_push($error_list,'Hay planes de extracción asociados a esta plantilla.');
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
