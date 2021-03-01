<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\ElementSubType;
use backend\models\Model;
use backend\models\Project;
use backend\models\SubElement;
use backend\models\SubModelElement;
use common\models\User;
use Yii;
use backend\models\Element;
use backend\models\ElementSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;

/**
 * ElementController implements the CRUD actions for Element model.
 */
class ElementController extends Controller
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
     * Lists all Element models.
     * @return mixed
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){

            $searchModel = new ElementSearch();
            $searchModel->id_project = $id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/element/element_delete.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $this->findModelProject($id_project),
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    /**
     * Displays a single Element model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $this->view->registerJsFile(Yii::$app->homeUrl . 'js/element/element_delete.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

            return $this->render('view', [
                'model' => $model,
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Creates a new Element model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new Element();
            $model->id_project = $id_project;
            $model->font = "Arial";
            $model->visibility = 1;
            $model->used = false;
            $modelSubElements = [new SubElement()];
            if ($model->load(Yii::$app->request->post())) {
                $model->save();
                $this->createSubElements($model,$modelSubElements);
                return $this->redirect(['view', 'id' => $model->id_element]);
            } else {
                $this->view->registerCssFile(Yii::$app->homeUrl . 'js/pick-a-color/pick-a-color-1.2.3.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerCssFile(Yii::$app->homeUrl . 'css/element.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/pick-a-color/tinycolor-0.9.15.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/pick-a-color/pick-a-color-1.2.3.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/element/element.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                return $this->render('create', [
                    'model' => $model,
                    'modelSubElements' => $modelSubElements,
                ]);
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }

    private function createSubElements($model, $modelSubElements)
    {
        $modelSubElements = Model::createMultiple(SubElement::classname(), $modelSubElements, 'id_sub_element');
        Model::loadMultiple($modelSubElements, Yii::$app->request->post());

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($flag = $model->save(false)) {
                foreach ($modelSubElements as $modelSubElement) {
                    $modelSubElement->id_element = $model->id_element;

                    if($modelSubElement->font_weight == "")
                        $modelSubElement->font_weight = "normal";

                    if($modelSubElement->font_style == "")
                        $modelSubElement->font_style = "normal";

                    if($modelSubElement->text_decoration == "")
                        $modelSubElement->text_decoration = "none";

                    if($modelSubElement->text_transform == "")
                        $modelSubElement->text_transform = "none";

                    if ($modelSubElement->validate())
                        if (!($flag = $modelSubElement->save(false))) {
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
    }

    /**
     * Updates an existing Element model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;

        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $modelSubElements = count($model->subElements)>0 ? $model->subElements : [new SubElement()];

            if ($model->visibility == false)
                $model->visibility = 0;

            if ($model->load(Yii::$app->request->post())) {
                $model->save();
                $this->updateSubElements($model,$modelSubElements);

                return $this->redirect(['view', 'id' => $model->id_element]);
            } else {
                $this->view->registerCssFile(Yii::$app->homeUrl . 'js/pick-a-color/pick-a-color-1.2.3.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/pick-a-color/tinycolor-0.9.15.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/pick-a-color/pick-a-color-1.2.3.min.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                $this->view->registerJsFile(Yii::$app->homeUrl . 'js/element/element.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

                $this->view->registerJsFile(Yii::$app->homeUrl.'js/project.js',['depends'=>[AppAsset::className()],'position'=>View::POS_HEAD]);

                return $this->render('update', [
                    'model' => $model,
                    'modelSubElements' => $modelSubElements,
                ]);
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    private function updateSubElements($model,$modelSubElements){

        $oldIDs = ArrayHelper::map($modelSubElements, 'id_sub_element', 'id_sub_element');
        $modelSubElements = Model::createMultiple(SubElement::classname(), $modelSubElements,'id_sub_element');
        Model::loadMultiple($modelSubElements, Yii::$app->request->post());
        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelSubElements, 'id_sub_element', 'id_sub_element')));

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($flag = $model->save(false)) {
                if (! empty($deletedIDs)) {
                    SubElement::deleteAll(['id_sub_element' => $deletedIDs]);
                }
                foreach ($modelSubElements as $modelSubElement) {
                    $modelSubElement->id_element = $model->id_element;

                    if($modelSubElement->font_weight == "")
                        $modelSubElement->font_weight = "normal";

                    if($modelSubElement->font_style == "")
                        $modelSubElement->font_style = "normal";

                    if($modelSubElement->text_decoration == "")
                        $modelSubElement->text_decoration = "none";

                    if($modelSubElement->text_transform == "")
                        $modelSubElement->text_transform = "none";

                    if (! ($flag = $modelSubElement->save(false))) {
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
    }

    /**
     * Deletes an existing Element model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){

            $subModelElement = SubModelElement::findAll(['id_element' => $id]);

            if (count($subModelElement)>0)
                return "Used";
            else{
                if ($model->delete())
                    return "Ok";
                else
                    return "Error";
            }

        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }


    public function actionDelete_view($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){

            $subModelElement = SubModelElement::findAll(['id_element' => $id]);

            if (count($subModelElement)>0)
                return "Used";
            else{
                if ($model->delete())
                    return $this->redirect(['index', 'id_project' => $id_project]);
                else
                    return "Error";
            }

        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }
    /**
     * Finds the Element model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Element the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Element::findOne($id)) !== null) {
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

    public function actionAjax_sub_element_list($q = null, $id=null){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => []];

        if(!is_null($id) && $id != ""){
            $list = ElementSubType::find()->where(['id_element_type' => $id, 'removed' => false])->andFilterWhere(['ilike', 'name', $q])->orderBy('name')->all();

            for ($i = 0; $i < count($list); $i++){
                $out['results'][$i]['id'] = $list[$i]->id_element_sub_type;
                $out['results'][$i]['text'] = $list[$i]->name;
            }

        }

        return $out;
    }

}
