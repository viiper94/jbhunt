<?php

namespace app\controllers;

use app\models\Trailers;
use app\models\TrailersCategories;
use app\models\TrailersForm;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\Response;

class TrailersController extends Controller{

    public function behaviors(){
        return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => false,
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
		$query = Trailers::find();
		if(Yii::$app->request->get('q')){
			$q = Yii::$app->request->get('q');
			$query->where(['like', 'name', $q])
				->orWhere(['like', 'description', $q]);
		}
		if(Yii::$app->request->get('game')){
			$game = Yii::$app->request->get('game');
			$query->andWhere(['game' => $game]);
		}
		if(Yii::$app->request->get('category')){
			$category = Yii::$app->request->get('category');
			$query->andWhere(['category' => $category]);
		}
		$total = $query->count();
		$pagination = new Pagination([
			'defaultPageSize' => 8,
			'totalCount' => $total
		]);
		$trailers = $query->orderBy(['name' => SORT_ASC])->offset($pagination->offset)->limit($pagination->limit)->all();
		$categories = TrailersCategories::find()->select(['name', 'title'])->orderBy(['title' => SORT_ASC])->indexBy('name')->asArray()->all();
		return $this->render('index',[
			'trailers' => $trailers,
			'currentPage' => Yii::$app->request->get('page', 1),
			'totalPages' => $pagination->getPageCount(),
			'pagination' => $pagination,
			'total' => $total,
			'categories' => $categories
		]);
    }

    public function actionAdd() {
		$model = new TrailersForm();
		$categories = TrailersCategories::find()->select(['name', 'title'])->indexBy('name')->asArray()->all();
		$new_cats[0] = 'Без категории';
		foreach ($categories as $category){
			$new_cats[$category['name']] = $category['title'];
		}
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addTrailer()){
				return $this->redirect(['trailers/index']);
			}
		}
		return $this->render('edit_trailer', [
			'model' => $model,
			'categories' => $new_cats
		]);
    }

    public function actionEdit(){
		$model = new TrailersForm(Yii::$app->request->get('id'));
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			if($model->editTrailer(Yii::$app->request->get('id'))) {
				return $this->goBack();
			}
		}
		$categories = TrailersCategories::find()->select(['name', 'title'])->orderBy(['title' => SORT_ASC])->asArray()->all();
		$new_cats[0] = 'Без категории';
		foreach ($categories as $category){
			$new_cats[$category['name']] = $category['title'];
		}
		return $this->render('edit_trailer', [
			'model' => $model,
			'categories' => $new_cats
		]);
    }

    public function actionRemove(){
		if(Trailers::deleteTrailer(Yii::$app->request->get('id'))){
			return $this->redirect(['trailers/index']);
		}else{
			return $this->goBack();
		}
    }

    public function actionGetinfo(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(Yii::$app->request->post('trailers')) {
                $trailers = Trailers::getTrailersInfo(Yii::$app->request->post('trailers'));
                return [
                    'status' => 'OK',
                    'trailers' => $trailers
                ];
            }
            return [
                'status' => 'Error'
            ];
        }else{
            return $this->render('//site/error');
        }
    }

}