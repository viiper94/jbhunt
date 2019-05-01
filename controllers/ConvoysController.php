<?php

namespace app\controllers;

use app\models\AddConvoyForm;
use app\models\Convoys;
use app\models\Trailers;
use app\models\TruckersMP;
use app\models\User;
use app\models\VtcMembers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class ConvoysController extends Controller{

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
        if(Yii::$app->request->get('id')){
            if(!$convoy = Convoys::find()
				->select(['convoys.*', 'trailers.name AS tr_name', 'trailers.picture AS tr_image', 'mods.file_name as tr_mod_file_name'])
				->leftJoin('trailers', 'trailers.id = convoys.trailer')
				->leftJoin('mods', 'trailers.id = mods.trailer')
				->where(['convoys.id' => Yii::$app->request->get('id')])
				->one()) return $this->render('//site/error');
            if($convoy->open == '0' && (Yii::$app->user->isGuest || !User::isVtcMember())){
            	return $this->render('//site/error', [
            		'meta' => [
            			[
							'property' => 'og:title',
							'content' => $convoy->title,
						],
						[
							'property' => 'og:image',
							'content' => 'https://'.$_SERVER['SERVER_NAME'].Yii::$app->request->baseUrl . '/images/trailers/' . $convoy->tr_image
						]
					]
				]);
			}
            $convoy->server = TruckersMP::getServerName($convoy->server);
            $convoy->participants = unserialize($convoy->participants);
            $participants = null;
            if($convoy->participants){
                $participants = array();
                foreach ($convoy->participants as $key => $participant){
                    foreach ($participant as $id){
                        if($id) {
                            $participants[$key][$id] = User::find()
                                ->select(['id', 'company', 'nickname', 'picture'])
                                ->where(['id' => $id])
                                ->one();
                        }
                    }
                }
            }
            return $this->render('convoy', [
            	'convoy' => $convoy,
                'participants' => $participants
            ]);
        }else{
            $archive_convoys = Convoys::getPastConvoys();
            $convoy_need_scores = array();
            if($archive_convoys){
                foreach($archive_convoys as $convoy){
                    if($convoy->scores_set == '0'){
                        $convoy_need_scores[] = $convoy;
                    }
                }
            }
            return $this->render('index', [
                'nearest_convoy' => Convoys::getNearestConvoy(),
                'convoys' => Convoys::getFutureConvoys(),
                'hidden_convoys' => $archive_convoys,
                'convoy_need_scores' => $convoy_need_scores
            ]);
        }
    }

    public function actionAdd(){
        if(User::canCreateConvoy()){
            $model = new AddConvoyForm();
			if(!User::isAdmin()){
				$model->communications = 'volvotrucks.ts-3.top';
				$model->visible = false;
				$model->attach_var_photo = true;
				$model->title = 'Закрытый конвой от [Volvo Trucks] '.Yii::$app->user->identity->nickname;
				$model->author = Yii::$app->user->identity->first_name.' '.Yii::$app->user->identity->last_name;
			}
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                if($id = $model->addConvoy()){
                    return $this->redirect(['convoys/index', 'id' => $id]);
                }else{
                    $model->addError('title', 'Ошибка при добавлении');
                }
            }
            return $this->render(User::isAdmin() ? 'edit_convoy' : 'member_edit_convoy', [
                'model' => $model,
                'trailers' => Trailers::getTrailers(['0' => 'Любой прицеп', '-1' => 'Без прицепа'], $model->game),
                'servers' => TruckersMP::getServersList($model->game)
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionEdit(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            $model = new AddConvoyForm(Yii::$app->request->get('id'));
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                if(!$model->editConvoy(Yii::$app->request->get('id'))){
                    $errors[] = 'Ошибка при редактировании';
                }
                return $this->redirect(['convoys/index', 'id' => Yii::$app->request->get('id')]);
            }else{
                return $this->render('edit_convoy', [
                    'model' => $model,
                    'trailers' => Trailers::getTrailers(['0' => 'Любой прицеп', '-1' => 'Без прицепа'], $model->game),
                    'servers' => TruckersMP::getServersList($model->game)
                ]);
            }
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionRemove(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            if(Convoys::deleteConvoy(Yii::$app->request->get('id'))){
                return $this->redirect(['convoys/index']);
            }else{
                $errors[] = 'Возникла ошибка';
            }
            return $this->redirect(['convoys/index', 'id' => Yii::$app->request->get('id')]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionHide(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            Convoys::visibleConvoy(Yii::$app->request->get('id'), 'hide');
            return $this->redirect(['convoys/index']);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionShow(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            Convoys::visibleConvoy(Yii::$app->request->get('id'), 'show');
            return $this->redirect(['convoys/index']);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionDeleteextrapicture(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            Convoys::deleteExtraPicture(Yii::$app->request->get('id'));
            return $this->redirect(['convoys/edit', 'id' => Yii::$app->request->get('id')]);
        }else{
            return $this->render('//site/error');
        }
    }

	public function actionDeletemap(){
		if(User::isAdmin() && Yii::$app->request->get('id')){
			Convoys::deleteMap(Yii::$app->request->get('id'));
			return $this->redirect(['convoys/edit', 'id' => Yii::$app->request->get('id')]);
		}else{
			return $this->render('//site/error');
		}
    }

    public function actionScores(){
        if(User::isAdmin() && Yii::$app->request->get('id')){
            if(Yii::$app->request->post()){
                $scores = Yii::$app->request->post('scores');
                $target = Yii::$app->request->post('month', false);
                $lead = Yii::$app->request->post('lead', null);
                if($scores && Convoys::setConvoyScores($scores, $target ? 'month' : 'other', $lead)){
                    $convoy = Convoys::findOne(Yii::$app->request->get('id'));
                    $convoy->scores_set = '1';
                    $convoy->update();
                }
                return $this->redirect(['convoys/index']);
            }
            return $this->render('scores', [
                'convoy' => Convoys::findOne(Yii::$app->request->get('id')),
                'all_members' => VtcMembers::getMembers()
            ]);
        }else{
            return $this->render('//site/error');
        }
    }

    public function actionParticipants(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $participants = Convoys::changeConvoyParticipants(
                Yii::$app->request->post('convoy_id'),
                Yii::$app->request->post('user_id'),
                Yii::$app->request->post('participate'));
            return [
                'status' => 'OK',
                'participants' => Convoys::getParticipantsData($participants)
            ];
        }else{
            return $this->render('//site/error');
        }
    }

}