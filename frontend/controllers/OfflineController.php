<?php

namespace frontend\controllers;

class OfflineController extends \yii\console\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionNotice()
    {
        exit('Maintainance');
    }
    
}
