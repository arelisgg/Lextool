<?php
/**
 * Created by PhpStorm.
 * User: Leo
 * Date: 20/12/2020
 * Time: 19:51
 */

namespace backend\controllers;

use backend\assets\AppAsset;
use backend\models\ElementType;
use backend\models\LemmaCandExtSearch;
use backend\models\LemmaExtPlan;
use backend\models\LemmaImage;
use backend\models\Letter;
use backend\models\Project;
use backend\models\Source;
use common\models\User;
use Codeception\Util\FileSystem;
use http\Exception;
use kartik\mpdf\Pdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use SebastianBergmann\Timer\Timer;
use Yii;
use backend\models\Lemma;
use backend\models\LemmaSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\View;

/**
 * Element_ext_taskController implements the CRUD actions for LemmaCandExt model.
 */
class Element_ext_taskController extends Controller
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
                    'image-delete' => ['POST'],
                    'upload-pdf-image' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LemmaCandExt models.
     * @return mixed
     * @throws NotAcceptableHttpException
     */
    public function actionIndex($id_ext_plan)
    {

        $searchModel = new LemmaCandExtSearch();
        $searchModel->id_lemma_ext_plan = $id_ext_plan;

        $ext_plan = LemmaExtPlan::findOne($id_ext_plan);

        $letters = $ext_plan->letters;

        $currentLetter = $letters[0];

        $project = Project::findOne(['id_project' => $ext_plan->id_project]);

//        if (User::userCanExtractionLemma($project->id_project)) {
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //$this->view->registerJsFile(Yii::$app->homeUrl . 'js/reloadLemmas.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);
        $this->view->registerJsFile(Yii::$app->homeUrl . 'js/loadSourcesLemma.js', ['depends' => [AppAsset::className()], 'position' => View::POS_HEAD]);


        return $this->render('index', [
            'letters' => $letters,
            'currentLetter' => $currentLetter,
            'ext_plan' => $ext_plan,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'project' => $project
        ]);
//        }else
//            throw new NotAcceptableHttpException('No tiene permitido ejecutar esta acción.');
    }


}