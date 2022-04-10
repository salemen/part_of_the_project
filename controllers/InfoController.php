<?php
// Раздел "Соглашения"
// TODO Хранить контент в БД и динамически отображать/менять

namespace app\controllers;

use Yii;
use yii\web\Controller;

class InfoController extends Controller
{
    public function actionAgent()
    {
        return $this->render('agent');
    }

    public function actionAgentPril()
    {
        return $this->render('agent-pril');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDatagree()
    {
        return $this->render('datagree');
    }

    public function actionPayType()
    {
        return $this->renderAjax('pay-type');
    }

    public function actionPolzovat()
    {
        return $this->render('polzovat');
    }

    public function actionSpravka()
    {
        return $this->render('spravka');
    }

    public function actionOkazuslug()
    {
        return $this->render('okazuslug');
    }
}