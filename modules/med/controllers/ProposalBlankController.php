<?php
namespace app\modules\med\controllers;

use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\helpers\AppHelper;
use yii\helpers\Url;
use app\models\proposal\ProposalCallDoctor;
use app\models\user\UserProposal;
use app\forms\proposal\CallDoctorForm;
use app\models\proposal\search\ProposalCallDoctor as ProposalCallDoctorSearch;

class ProposalBlankController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new ProposalCallDoctorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model'=>$model
        ]);
    }

    public function actionCreateAdmin($proposal_id = null)
    {
        $model =  new ProposalCallDoctor(['proposal_id'=>$proposal_id]);

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionCreate($proposal_id = null)
    {
        $model = ProposalCallDoctor::findOne(['proposal_id'=>$proposal_id]) ?  : new ProposalCallDoctor(['proposal_id'=>$proposal_id]);

        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->updateProposal($model)) {
            return $this->redirect(['view', 'id'=>$model->id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionCreateNew($model = null)
    {
        $model = ProposalCallDoctor::findOne(['proposal_id'=>$model]) ? : new ProposalCallDoctor(['proposal_id'=>$model]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id'=>$model->id]);
        }
        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionNewCreate($model = null)
    {
        $model = new ProposalCallDoctor(['proposal_id'=>$model]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id'=>$model->id]);
        }

        return $this->render('_form-new', [
            'model'=>$model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->updateProposal($model)) {
            return $this->redirect(['view', 'id'=>$model->id]);
        }

        return $this->render('_form', [
            'model'=>$model
        ]);
    }

    public function actionPrint($id)
    {
        $model = $this->findModel($id);
        $process = new TemplateProcessor('../modules/med/forms/phpoffice/calldoctor.docx');

        $process->setValue('id', $model->id);
        $process->setValue('date', date('d.m.Y г.', $model->created_at));
        $process->setValue('fullname', implode(' ', [$model->user_f, $model->user_i, $model->user_o]));
        $process->setValue('birthday', $model->user_birth);
        $process->setValue('age', AppHelper::calculateAge($model->user_birth, true));
        $process->setValue('address', $model->address);
        $process->setValue('guide', $model->guide);
        $process->setValue('reason', $model->reason);
        $process->setValue('who_calls', $model->who_calls);
        $process->setValue('phone', $model->phone);
        $process->setValue('complaint', $model->complaint);
        $process->setValue('visit_time', $model->visit_time);
        $process->setValue('cost', $model->cost);
        if(isset($model->proposal->employee->data)) {
            $process->setValue('polis_oms_number', $model->proposal->employee->data->polis_oms_number);
        }elseif(isset($model->proposal->patient->data)) {
            $process->setValue('polis_oms_number', $model->proposal->patient->data->polis_oms_number);
        }
        if(isset($model->department->alias)){
            $process->setValue('dep', $model->department->alias);
        }
        elseif(isset($model->proposal->employee->data)){
            $process->setValue('dep', $model->proposal->employee->data->clinic);
        }
        elseif(isset($model->proposal->patient->data)){
            $process->setValue('dep', $model->proposal->patient->data->clinic);
        }
        $process->setValue('created_by', AppHelper::shortFullname($model->creater->fullname));

        $file = 'temp/' . date('U') . '.docx';
        $process->saveAs($file);

        return $this->redirect(Url::to($file, 'https'));
    }

    protected function findModel($id)
    {
        if (($model = ProposalCallDoctor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }

    protected function updateProposal($model)
    {
        $proposal_id = $model->proposal_id;

        if ($proposal_id !== null) {
            $model = UserProposal::findOne(['id'=>$proposal_id]);
            $model->status = UserProposal::STATUS_SUCCESS;

            return $model->save();
        }

        return true;
    }
}