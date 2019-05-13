<?php

namespace backend\controllers;

use backend\models\Project;
use common\models\User;
use Yii;
use backend\models\ComplementaryDoc;
use backend\models\ComplementaryDocSearch;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Complementary_docController implements the CRUD actions for ComplementaryDoc model.
 */
class Complementary_docController extends Controller
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
            $searchModel = new ComplementaryDocSearch();
            $searchModel->id_project = $id_project;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $this->findModelProject($id_project),
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');

    }


    public function actionAccepted($id)
    {
        $model = $this->findModel($id);
        $id_project = $model->id_project;
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            if ($model->load(Yii::$app->request->post())) {

                if ($model->accepted == true){
                    $model->accepted = false;
                } else {
                    $model->accepted = true;
                }
                $model->save(false);

                //return $this->redirect(['index', 'id_project' => $model->id_project]);
                return $this->redirect(['accepted', 'id' => $model->id_complementary_doc]);
            }

            return $this->render('update', [
                'model' => $model,
                'project' => $this->findModelProject($model->id_project),
            ]);
        }else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }


    /**
     * Finds the ComplementaryDoc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ComplementaryDoc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ComplementaryDoc::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelProject($id_project)
    {
        if (($model = Project::findOne($id_project)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
