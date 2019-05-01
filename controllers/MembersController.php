<?php

namespace app\controllers;

use app\models\Achievements;
use app\models\AchievementsProgress;
use app\models\MemberForm;
use app\models\User;
use app\models\VtcMembers;
use app\models\VtcPositions;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class MembersController extends Controller{

	public function behaviors(){
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index', 'stats'],
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
        return $this->render('index', [
            'all_members' => VtcMembers::getAllMembers()
        ]);
    }

    public function actionStats(){
        return $this->render('stats', [
            'all_members' => VtcMembers::getMembers(false)
        ]);
    }

    public function actionEdit(){
        if(Yii::$app->request->get('id') && User::isAdmin()){
            $model = new MemberForm(Yii::$app->request->get('id'));
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                if($model->editMember(Yii::$app->request->get('id'))){
                    return $this->redirect(['members/edit', 'id' => Yii::$app->request->get('id')]);
                }
            }
            $positions = VtcPositions::find()->select(['id', 'name'])->asArray()->all();
            return $this->render('edit', [
                'model' => $model,
                'positions' => ArrayHelper::map($positions, 'id', 'name'),
                'all_achievements' => Achievements::find()->all(),
                'achievements_progress' => AchievementsProgress::find()
                    ->select(['ach_id'])
                    ->where(['uid' => $model->member->user_id, 'complete' => 1])
                    ->asArray()->all()
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionReset(){
    	if(User::isAdmin()){
			VtcMembers::zeroScores();
			VtcMembers::cleanVacations();
			return $this->redirect(['members/stats']);
		}else{
			return $this->render('//site/error');
		}
    }

    public function actionDismiss(){
        if(Yii::$app->request->get('id') && User::isAdmin()){
            VtcMembers::fireMember(Yii::$app->request->get('id'));
            return $this->redirect(['members/stats']);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionGetbans() {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(Yii::$app->request->post('steamid64')){
                return [
                    'bans' => VtcMembers::getBans(Yii::$app->request->post('steamid64')),
                    'status' => 'OK'
                ];
            }
            return [
                'status' => 'Error, no Steam IDs to check'
            ];
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionResort(){
        if(Yii::$app->request->get('id') && User::isAdmin()
            && (Yii::$app->request->get('dir') == 'down' || Yii::$app->request->get('dir') == 'up')){
            VtcMembers::resortMembers(Yii::$app->request->get('id'));
            return $this->redirect(['members/index']);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionStep4(){
        if(Yii::$app->request->isAjax && Yii::$app->request->post('uid')){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(Yii::$app->request->post('complete') == 'true'){
                VtcMembers::step4Complete(Yii::$app->request->post('uid'));
            }
            return [
                'status' => 'OK'
            ];
        }else{
            return $this->render('//site/error');
        }
    }

}