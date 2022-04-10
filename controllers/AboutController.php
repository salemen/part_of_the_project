<?php
// Раздел "Информация" 
// TODO Хранить контент в БД и динамически отображать/менять

namespace app\controllers;

use Yii;
use yii\web\Controller;

class AboutController extends Controller
{    
    public function actionIndex()
    {
        return $this->render('_main');
    }
    
    public function actionContact()
    {
        return $this->render('_main');
    }
    
    public function actionDocuments()
    {
        return $this->render('_main');
    }
    
    public function actionLicense()
    {
        return $this->render('_main');
    }
    
    public function actionPaysecure()
    {
        return $this->render('_main');
    }
    
    public function actionQuestions()
    {
        return $this->render('_main');
    }
    
    public function actionService()
    {
        return $this->render('_main');
    }
    
    public function actionVacancy()
    {
        return $this->render('_main');
    }

    public function actionInfo()
    {
        return $this->render('info');
    }
    public function actionSpravka()
    {
        return $this->render('spravka');
    }
    public function actionPatientHelp()
    {
        return $this->render('patient-help');
    }
}