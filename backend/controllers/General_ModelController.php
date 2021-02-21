<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\Element;
use backend\models\ElementSeparator;
use backend\models\LexArticle;
use backend\models\Project;
use backend\models\Separator;
use backend\models\SubModel;
use backend\models\SubModelElement;
use backend\models\SubModelSeparator;
use common\models\User;
use Throwable;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\View;

/**
 * General_modelController implements the CRUD actions for SubModel model.
 */
class General_modelController extends Controller
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
     * Lists all SubModel models.
     * @param $id_project
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $project = Project::findOne($id_project);

            $submodels = SubModel::find()->where(['id_project' => $id_project])->all();

            $exist = false;
            foreach ($submodels as $subModel) {
                if ($subModel->order != null && $subModel->order > 0) {
                    $exist = true;
                    break;
                }
            }
            $general_model = [];

            if ($exist) {
                $general_model_submodels = SubModel::find()->where(['id_project' => $id_project])->andWhere(['>','order',0])->orderBy('order')->all();

                foreach ($general_model_submodels as $submodel){
                    $submodel_separators = SubModelSeparator::find()->where(['id_sub_model' => $submodel->id_sub_model])->orderBy('order')->all();
                    foreach ($submodel_separators as $separator) {
                        $sep = Separator::findOne($separator->id_separator);
                        array_push($general_model,$sep);
                    }
                    array_push($general_model,$submodel);
                }

                $this->view->registerCssFile(Yii::$app->homeUrl . 'css/general_model_view.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/general_model_details.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/general_model_delete.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                return $this->render('index', compact('project','general_model','exist'));
            }else {
                return $this->render('index', compact('project','general_model','exist'));
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');


    }

    /**
     * Creates a new SubModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $id_project
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $project = Project::findOne($id_project);
            $submodels = SubModel::find()->where(['id_project' => $id_project])->all();
            $separators = Separator::find()->where(['id_project'=> $id_project,'scope' => ["Componente"]])->all();

            if (Yii::$app->request->post()) {
                $posts = Yii::$app->request->post();

                $posts = array_reverse($posts);

                $order = count($posts) - 1;

                $id_submodel = "";

                foreach ($posts as $key => $value) {

                    $key_val = explode('-',$key);

                    if ($key_val[0] == "submodel") {
                        $submodel = SubModel::findOne($value);
                        $submodel->order = $order;
                        $submodel->save();
                        $id_submodel = $submodel->id_sub_model;
                    } else if ($key_val[0] == "separator") {
                        $submodel_separator = new SubModelSeparator();
                        $submodel_separator->id_separator = $value;
                        $submodel_separator->id_sub_model = $id_submodel;
                        $submodel_separator->order = $order;
                        $submodel_separator->save();
                    }
                    $order--;
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

            return $this->render('create', compact('project','submodels','separators'));
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Updates an existing SubModel model.
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
     * Deletes an existing SubModel model.
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
