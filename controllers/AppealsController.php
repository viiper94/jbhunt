<?php

namespace app\controllers;

use app\models\AppealForm;
use app\models\Appeals;
use app\models\User;
use app\models\VtcMembers;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class AppealsController extends Controller{

	public function behaviors(){
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['add', 'thx'],
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
        if(User::isAdmin()){
            $query = Appeals::find()
				->select([
					'appeals.*',
					'app_user.picture as appealed_user_picture',
					'app_user.nickname as appealed_user_nickname',
					'app_user.company as appealed_user_company',
					'from_user.company as from_user_company',
					'from_user.nickname as from_user_nickname',
					'from_user.first_name as from_user_first_name',
					'from_user.last_name as from_user_last_name'
				])
				->leftJoin('users as app_user', 'app_user.id = appeals.appeal_to_user_id')
				->leftJoin('users as from_user', 'from_user.id = appeals.uid')
				->orderBy(['date' => SORT_DESC]);
            $total = $query->count();
            $pagination = new Pagination([
                'defaultPageSize' => 10,
                'totalCount' => $total
            ]);
            $appeals = $query->offset($pagination->offset)->limit($pagination->limit)->all();
            return $this->render('index', [
                'appeals' => $appeals,
                'currentPage' => Yii::$app->request->get('page', 1),
                'totalPages' => $pagination->getPageCount(),
                'pagination' => $pagination,
                'total' => $total,
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionAdd(){
        $model = new AppealForm();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            if($model->addAppeal()) return $this->redirect(['appeals/thx']);
            else $model->addError('uid', 'Возникла ошибка');
        }
        return $this->render('add', [
            'model' => $model,
            'members' => array_replace(['' => 'Выберите сотрудника'], VtcMembers::getMembersArray())
        ]);
    }

    public function actionThx(){
        return $this->render('thx');
    }

    public function actionViewed(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            Appeals::viewedAppeal(Yii::$app->request->get('id'));
            return $this->redirect(['appeals/index']);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionRemove(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            Appeals::removeAppeal(Yii::$app->request->get('id'));
            return $this->redirect(['appeals/index']);
        }else{
            return $this->render('//site/error');
        }
    }

}