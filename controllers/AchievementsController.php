<?php

namespace app\controllers;

use app\models\AchievementsProgress;
use app\models\Mail;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use app\models\User;
use app\models\Achievements;
use app\models\AchievementsForm;
use yii\web\Response;

class AchievementsController extends Controller{

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
        if(User::isVtcMember()){
            $query = Achievements::find()
				->select(['achievements.*', 'related.id as related', 'related.title as r_title'])
				->leftJoin('achievements as related', 'achievements.related = related.id');
            if(!User::isAdmin()) $query = $query->where(['achievements.visible' => '1']);
            if(Yii::$app->request->get('q')){
                $q = Yii::$app->request->get('q');
                $query->where(['like', 'achievements.title', $q])
                    ->orWhere(['like', 'achievements.description', $q]);
            }
            $user_ach_progress = AchievementsProgress::find()->select(['ach_id', 'complete'])->where(['uid' => Yii::$app->user->id])->asArray()->all();
            $total = $query->count();
            $pagination = new Pagination([
                'defaultPageSize' => 15,
                'totalCount' => $total
            ]);
            $moderate_count = 0;
            if(User::isAdmin()){
                $moderate_count = AchievementsProgress::find()
					->innerJoin('achievements', 'achievements_progress.ach_id = achievements.id')
					->innerJoin('users', 'achievements_progress.uid = users.id')
					->innerJoin('vtc_members', 'users.id = vtc_members.user_id')
					->where(['achievements_progress.complete' => 0])
					->count();
            }
            return $this->render('index', [
                'achievements' => $query->orderBy(['sort' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all(),
                'user_complete_ach' => unserialize(Yii::$app->user->identity->achievements),
                'user_ach_progress' => $user_ach_progress,
                'currentPage' => Yii::$app->request->get('page', 1),
                'totalPages' => $pagination->getPageCount(),
                'pagination' => $pagination,
                'total' => $total,
                'moderate_count' => $moderate_count
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionAdd(){
        if(User::isAdmin()){
            $model = new AchievementsForm();
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                if($model->addAchievement()){
                    return $this->redirect(['index']);
                }else{
                    $model->addError('title', 'Возникла ошибка');
                }
            }
            return $this->render('add_achievement', [
                'model' => $model,
                'related' => ArrayHelper::map(Achievements::find()->asArray()->all(), 'id', 'title')
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionEdit(){
        if(User::isAdmin()){
            $model = new AchievementsForm(Yii::$app->request->get('id'));
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                if($model->editAchievement(Yii::$app->request->get('id'))){
                    return $this->redirect(['index']);
                }else{
                    $model->addError('title', 'Возникла ошибка');
                }
            }
            return $this->render('add_achievement', [
                'model' => $model,
                'related' => ArrayHelper::map(Achievements::find()->asArray()->all(), 'id', 'title')
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionRemove(){
        if(User::isAdmin()){
            Achievements::removeAchievement(Yii::$app->request->get('id'));
            return $this->redirect(['achievements/index']);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionSort(){
        if(User::isAdmin()){
            Achievements::resortAchievement(Yii::$app->request->get('id'), Yii::$app->request->get('operation'));
            return $this->redirect(['achievements/index']);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionGet(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $progress = AchievementsProgress::getAchievement(Yii::$app->user->id, Yii::$app->request->get('achid'), $_FILES[0]);
            if($progress && AchievementsProgress::find()->where(['complete' => 0])->count() == 1) Mail::newAchievementToAdmin();
			return [
				'status' => $progress ? 'OK' : 'Error'
			];
        }
		return false;
    }

    public function actionModerate(){
        if(User::isAdmin()){
            $query = AchievementsProgress::find()
				->select([
						'achievements_progress.id',
						'achievements_progress.ach_id',
						'achievements_progress.uid',
						'achievements_progress.proof',
						'achievements_progress.complete',
						'achievements.title',
						'achievements.description',
						'achievements.image',
						'users.company as u_company',
						'users.nickname as u_nickname',
						'vtc_members.id as member_id'
					])
				->innerJoin('achievements', 'achievements_progress.ach_id = achievements.id')
				->innerJoin('users', 'achievements_progress.uid = users.id')
				->innerJoin('vtc_members', 'users.id = vtc_members.user_id');
            $total = $query->count();
            $pagination = new Pagination([
                'defaultPageSize' => 10,
                'totalCount' => $total
            ]);
            $progress = $query->orderBy([
					'achievements_progress.complete' => SORT_ASC,
					'achievements_progress.id' => SORT_DESC
				])
				->offset($pagination->offset)->limit($pagination->limit)->all();
            return $this->render('moderate_achievements', [
                'progress' => $progress,
                'currentPage' => Yii::$app->request->get('page', 1),
                'totalPages' => $pagination->getPageCount(),
                'pagination' => $pagination,
                'total' => $total
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionApply(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            AchievementsProgress::applyAchievement(Yii::$app->request->get('id'));
            return $this->redirect(['achievements/moderate']);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionDeny(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            AchievementsProgress::denyAchievement(Yii::$app->request->get('id'));
            return $this->redirect(['achievements/moderate']);
        }else{
            return $this->render('//site/error');
        }
    }

	public function actionDelete(){
		if(User::isAdmin() && Yii::$app->request->get('id')){
			AchievementsProgress::deleteAchievement(Yii::$app->request->get('id'));
			return $this->redirect(['achievements/moderate']);
		}
		return $this->render('//site/error');
    }

}