<?php

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\IllustrationPlan;
use backend\models\IllustrationRevPlan;
use backend\models\Project;
use Yii;
use backend\models\Illustration;
use backend\models\IllustrationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;

/**
 * IllustrationController implements the CRUD actions for Illustration model.
 */
class IllustrationController extends Controller
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

    public function actionPlans($id_project)
    {
        $project = $this->findModelProject($id_project);
        $user = Yii::$app->user;

        $illustration_plans1 = IllustrationPlan::find()->where(['id_user' => $user->id, 'type'=>'Lema', 'id_project' => $id_project ,'finished' => false])->all();
        $illustration_plans2 = IllustrationPlan::find()->where(['id_user' => $user->id, 'type'=>'Documento Complementario' ,'id_project' => $id_project ,'finished' => false])->all();

        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->render('plans', [
            'illustration_plans1' => $illustration_plans1,
            'illustration_plans2' => $illustration_plans2,
            'project' => $project,
        ]);
    }

    public function actionRevplans($id_project)
    {
        $project = $this->findModelProject($id_project);
        $user = Yii::$app->user;

        $illustration_rev_plans1 = IllustrationRevPlan::find()->joinWith('illustrationPlan')->where(['illustration_rev_plan.id_user' => $user->id, 'type'=>'Lema', 'illustration_rev_plan.id_project' => $id_project ,'illustration_rev_plan.finished' => false])->all();
        $illustration_rev_plans2 = IllustrationRevPlan::find()->joinWith('illustrationPlan')->where(['illustration_rev_plan.id_user' => $user->id, 'type'=>'Documento Complementario' ,'illustration_rev_plan.id_project' => $id_project ,'illustration_rev_plan.finished' => false])->all();

        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/assets/owl.theme.green.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        // $this->view->registerCssFile(Yii::$app->homeUrl . 'js/owl-carousel/docs/assets/css/docs.theme.min.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerCssFile(Yii::$app->homeUrl . 'css/carousel.css', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/owl-carousel/dist/owl.carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/init-carousel.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);

        return $this->render('revplans', [
            'illustration_rev_plans1' => $illustration_rev_plans1,
            'illustration_rev_plans2' => $illustration_rev_plans2,
            'project' => $project,
        ]);
    }


    public function actionFinish($id_illustration_plan){
        $plan = IllustrationPlan::findOne($id_illustration_plan);
        $plan->finished = true;
        $plan->save(false);

        return $this->redirect(['plans', 'id_project' => $plan->id_project]);
    }

    public function actionRevfinish($id_illustration_rev_plan){
        $plan = IllustrationRevPlan::findOne($id_illustration_rev_plan);
        $plan->finished = true;
        $plan->save(false);

        return $this->redirect(['revplans', 'id_project' => $plan->id_project]);
    }


    protected function findModelProject($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página pedida no existe.');
    }
}
