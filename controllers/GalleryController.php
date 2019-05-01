<?php

namespace app\controllers;

use app\models\Gallery;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class GalleryController extends Controller{

	public function behaviors(){
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index'],
						'allow' => true,
						'roles' => ['?']
					],
					[
						'allow' => true,
						'roles' => ['@']
					],
				],
			],
		];
	}

    public function actionIndex(){
		$photos = Gallery::find();
		if(!User::isAdmin()) $photos = $photos->where(['visible' => '1'])->orWhere(['uploaded_by' => Yii::$app->user->id]);
		$photos = $photos->orderBy(['visible' => SORT_DESC, 'sort' => SORT_DESC])->all();
        return $this->render('index', [
        	'photos' => $photos
		]);
    }

	public function actionUpload(){
		if(Yii::$app->request->isAjax && User::isVtcMember(Yii::$app->request->get('uid'))){
			Yii::$app->response->format = Response::FORMAT_JSON;
			return [
				'status' => User::isAdmin() ? '1' : '2',
				'image' => Gallery::addImageToGallery($_FILES[0], $_POST['description'], Yii::$app->request->get('uid')),
				'files' => $_FILES[0],
				'post' => $_POST,
			];
		}else{
			return $this->render('//site/error');
		}
    }

	public function actionRemove(){
		if(User::isAdmin() && Yii::$app->request->get('id')){
			Gallery::removePhoto(Yii::$app->request->get('id'));
			return $this->redirect(['gallery/index']);
		}else{
			return $this->render('//site/error');
		}
    }

	public function actionShow(){
		if(User::isAdmin() && Yii::$app->request->get('id')){
			Gallery::visiblePhoto(Yii::$app->request->get('id'), 'show');
			return $this->redirect(['gallery/index']);
		}else{
			return $this->render('//site/error');
		}
    }

	public function actionHide(){
		if(User::isAdmin() && Yii::$app->request->get('id')){
			Gallery::visiblePhoto(Yii::$app->request->get('id'), 'hide');
			return $this->redirect(['gallery/index']);
		}else{
			return $this->render('//site/error');
		}
    }

	public function actionSort(){
		if(User::isAdmin() && Yii::$app->request->get('id') && Yii::$app->request->get('operation')){
			Gallery::resortPhoto(Yii::$app->request->get('id'), Yii::$app->request->get('operation'));
			return $this->redirect(['gallery/index']);
		}else{
			return $this->render('//site/error');
		}
    }

}