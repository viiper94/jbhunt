<?php

namespace app\controllers;

use app\models\User;
use app\models\VariationsForm;
use Yii;
use yii\web\Controller;
use app\models\Variations;

class VariationsController extends Controller{

    public function actionIndex(){
        return $this->render('index', [
            'variations' => Variations::findAll(['game' => Yii::$app->request->get('game')])
        ]);
    }

    public function actionEdit(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            $variation = Variations::findOne(Yii::$app->request->get('id'));
            if(Yii::$app->request->post()){
                if(!$variation->editVariation()){
                    $errors[] = 'Ошибка при редактировании';
                }
                return $this->redirect(['variations/index', 'game' => $variation->game]);
            }else{
                return $this->render('edit', [
                    'variation' => $variation,
                    'action' => 'edit'
                ]);
            }
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionAdd(){
        if(User::isAdmin()){
            $variation = new Variations();
            if(Yii::$app->request->post()){
                if(!$variation->editVariation()){
                    $errors[] = 'Ошибка';
                }
                return $this->redirect(['variations/index', 'game' => $variation->game]);
            }else{
                return $this->render('edit', [
                    'variation' => $variation,
                    'action' => 'add'
                ]);
            }
        }else{
            return $this->render('//site/error');
        }
    }

}