<?php
namespace app\modules\med\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\user\UserProposal;

class ProposalController extends Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query'=>UserProposal::find()->orderBy(['created_at'=>SORT_DESC]),
            'sort'=>false
        ]);

        return $this->render('index', [
            'dataProvider'=>$dataProvider
        ]);
    }

    public function actionAdmin($id = null)
    {
        return $this->redirect(['proposal-blank/create','proposal_id'=>$id]);
    }
    
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        if ($model->status == UserProposal::STATUS_ONHOLD) {
            $model->updateAttributes([
                'status'=>UserProposal::STATUS_ONWORK,
                'updated_by'=>Yii::$app->user->id
            ]);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        
        return $this->render('view', [
            'model'=>$model
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->updateAttributes(['status'=>0]);

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = UserProposal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не найдена.');
    }
}