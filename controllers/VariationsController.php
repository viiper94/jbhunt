<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class VariationsController extends Controller{

    public function actionIndex(){
        return $this->render(Yii::$app->request->get('game') == 'ats' ? 'variations_ats' : 'variations_ets');
    }

}