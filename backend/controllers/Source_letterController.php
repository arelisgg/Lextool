<?php

namespace backend\controllers;

use Yii;
use backend\models\SourceLetter;
use backend\models\SourceLetterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Source_letterController implements the CRUD actions for SourceLetter model.
 */
class Source_letterController extends Controller
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
     * Lists all SourceLetter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SourceLetterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SourceLetter model.
     * @param integer $id_letter
     * @param integer $id_source
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_letter, $id_source)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_letter, $id_source),
        ]);
    }

    /**
     * Creates a new SourceLetter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SourceLetter();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_letter' => $model->id_letter, 'id_source' => $model->id_source]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SourceLetter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_letter
     * @param integer $id_source
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_letter, $id_source)
    {
        $model = $this->findModel($id_letter, $id_source);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_letter' => $model->id_letter, 'id_source' => $model->id_source]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SourceLetter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_letter
     * @param integer $id_source
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_letter, $id_source)
    {
        $this->findModel($id_letter, $id_source)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SourceLetter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_letter
     * @param integer $id_source
     * @return SourceLetter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_letter, $id_source)
    {
        if (($model = SourceLetter::findOne(['id_letter' => $id_letter, 'id_source' => $id_source])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
