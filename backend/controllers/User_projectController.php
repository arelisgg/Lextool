<?php

namespace backend\controllers;

use backend\models\Project;
use common\models\User;
use Yii;
use backend\models\UserProject;
use backend\models\UserProjectSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * User_projectController implements the CRUD actions for UserProject model.
 */
class User_projectController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','update','view','delete'],
                'rules' => [
                    [
                        'actions' => ['index','create','update','view','delete'],
                        'allow' => true,
                        'roles'=> ['Jefe de Proyecto'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all UserProject models.
     * @return mixed
     */
    public function actionIndex($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $searchModel = new UserProjectSearch();
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

    /**
     * Displays a single UserProject model.
     * @param integer $id_project
     * @param integer $id_user
     * @param string $role
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_project, $id_user, $role)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            return $this->renderAjax('view', [
                'model' => $this->findModel($id_project, $id_user, $role),
            ]);
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Creates a new UserProject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_project)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = new UserProject();
            $model->id_project = $id_project;

            if ($model->load(Yii::$app->request->post())) {
                if ($model->role != "Jefe de Proyecto"){
                    if ($model->validate()){
                        if($model->save())
                            return "guardado";
                        else
                            return "Error";
                    } else
                        return "Exist";
                } else
                    return "Boss";
            } else {
                return $this->renderAjax('create', [
                    'model' => $model,
                ]);
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Updates an existing UserProject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_project
     * @param integer $id_user
     * @param string $role
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_project, $id_user, $role)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = $this->findModel($id_project, $id_user, $role);

            if ($model->load(Yii::$app->request->post())) {
                if ($model->role == "Jefe de Proyecto" &&
                    $model->id_user_project != UserProject::findOne(['id_project' => $id_project, 'role' => "Jefe de Proyecto"])->id_user_project){
                    return "Boss";

                } else {
                    if ($model->validate()){
                        if($model->save())
                            return "editado";
                        else
                            return "Error";
                    } else
                        return "Exist";
                }

            } else {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Deletes an existing UserProject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_project
     * @param integer $id_user
     * @param string $role
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_project, $id_user, $role)
    {
        if(User::userCanProjectAndRol($id_project, "Jefe de Proyecto")){
            $model = $this->findModel($id_project, $id_user, $role);

            if ($model->role != "Jefe de Proyecto"){
                $lemmaExtPlans = $model->user->lemmaExtPlans;
                $lemmaRevPlans = $model->user->lemmaRevPlans;
                $docExtPlans = $model->user->docExtPlans;
                $docMakePlans = $model->user->docMakePlans;
                $docRevPlans = $model->user->docRevPlans;
                $redactionPlans = $model->user->redactionPlans;
                $revisionPlans = $model->user->revisionPlans;
                $illustrationPlans = $model->user->illustrationPlans;
                $illustrationRevPlans = $model->user->illustrationRevPlans;

                if(count($lemmaExtPlans) > 0 || count($lemmaRevPlans)  > 0 || count($docExtPlans)  > 0 || count($docMakePlans) > 0
                    || count($docRevPlans) > 0 || count($redactionPlans)  > 0 || count($revisionPlans)  > 0 ||count($illustrationPlans) > 0
                    || count($illustrationRevPlans)  > 0){
                    return "Used";
                } else {
                    if ($model->delete())
                        return "Ok";
                    else
                        return "Error";
                }

            } else {
                return "Boss";
            }

        } else
            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }

    /**
     * Finds the UserProject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_project
     * @param integer $id_user
     * @param string $role
     * @return UserProject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_project, $id_user, $role)
    {
        if (($model = UserProject::findOne(['id_project' => $id_project, 'id_user' => $id_user, 'role' => $role])) !== null) {
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
}
