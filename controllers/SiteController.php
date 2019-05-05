<?php

namespace app\controllers;

use app\models\Mail;
use app\models\Notifications;
use app\models\Other;
use app\models\PasswordForm;
use app\models\Recaptcha;
use app\models\Steam;
use app\models\TruckersMP;
use app\models\ResetForm;
use Yii;
use app\models\RecruitForm;
use app\models\SignupForm;
use app\models\ProfileForm;
use app\models\User;
use app\models\VtcMembers;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\web\Response;

class SiteController extends Controller{

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'recruit'],
                'rules' => [
                    [
                        'actions' => ['logout', 'recruit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    public function actionIndex(){
        return $this->render('index');
    }

    public function actionNotifications(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(Yii::$app->request->get('ajax-action') == 'mark_notifications'){
                foreach (Yii::$app->request->post('id') as $id){
                    if(!Notifications::markNotification($id)){
                        return [
                            'status' => 'Error'
                        ];
                    }
                }
                return [
                    'status' => 'OK'
                ];
            }
            if(Yii::$app->request->get('ajax-action') == 'delete_notification'){
                if(!Notifications::deleteNotification(Yii::$app->request->post('id'))){
                    return [
                        'status' => 'Error'
                    ];
                }
                return [
                    'status' => 'OK'
                ];
            }
        }
        return $this->render('error');
    }

    public function actionRules(){
        $edit = false;
        $errors = array();
        if(Yii::$app->request->get('action') == 'edit' && User::isAdmin()){
            if(Yii::$app->request->post('rules')){
                if(Other::updateRules(Yii::$app->request->post('rules'))){
                    if(Yii::$app->request->post('notify') == '1'){
                        Notifications::addNotificationsToMembers('Изменения в правилах ВТК!');
                    }
                    return $this->redirect(['site/rules']);
                }
                else $errors[] = 'Ошибка при сохранении!';
            }
            $edit = true;
        }
        $rules = Other::findOne(['category' => 'rules']);
        return $this->render('rules', [
            'rules' => $rules,
            'edit' => $edit,
            'errors' => $errors
        ]);
    }

	public function actionExams(){
		return $this->render('exams');
    }

    public function actionRecruit(){
		$model = new RecruitForm();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addClaim()) return $this->redirect(['site/claims']);
		}
		$rules = '';
		$members = null;
		$step = Yii::$app->request->get('step');
		if($step == '2') $rules = Other::findOne(['category' => 'rules']);
		if($step == '3') $members = array_replace(['' => 'Никто не приглашал / Другой человек'], VtcMembers::getMembersArray());
		return $this->render('recruit', [
			'model' => $model,
			'step' => $step == '' || $step > 3 ? '1' : $step,
			'rules' => $rules,
			'members' => $members
		]);
    }

    public function actionSignup(){
        if(Yii::$app->request->isAjax && Yii::$app->request->get('ajax-action') == 'get_truckersmpid'){
            $steam_id = Steam::getUser64ID(Yii::$app->request->post('steam_url'));
            $id = TruckersMP::getUserID($steam_id);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => 'OK',
                'steamid' => $steam_id,
                'url' => $id ? 'https://truckersmp.com/user/'. $id .'/' : false
            ];
        }
        if(!Yii::$app->user->isGuest){
            return $this->redirect(['site/profile', 'id' => Yii::$app->user->id]);
        }
        $model = new SignupForm();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            if(Recaptcha::verifyCaptcha(Yii::$app->request->post('g-recaptcha-response'))){
                if($id = $model->signup()){
                    Yii::$app->user->login(User::findByUsername($model->username), 3600*24*30);
                    return $this->redirect(['site/profile', 'id' => $id]);
                }else{
                    $model->addError('attr', 'Ошибка при регистрации');
                }
            }else{
                $model->addError('attr', 'Капча не верифицирована!');
            }
        }
        return $this->render('signup', [
            'model' => $model
        ]);
    }
    
    public function actionLogin(){
        if(!Yii::$app->user->isGuest){
        	if(Yii::$app->user->returnUrl) return $this->goBack();
            return $this->redirect(['site/profile', 'id' => Yii::$app->user->id]);
        }
        if(Yii::$app->request->isAjax && Yii::$app->request->get('ajax-action') == 'reset_password'){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $string = User::generatePasswordResetString(Yii::$app->request->post('email'));
            $mailed = $string && Mail::sendResetPassword($string, Yii::$app->request->post('email')) ? 'OK' : 'Error';
            return [
                'status' => $mailed,
            ];
        }
		$model = new LoginForm();
        if(Yii::$app->request->get('social') == 'steam'){
			if($steamid = Steam::authUser()){
				 if(User::loginBySteamId($steamid)) return $this->goBack();
			}
			return $this->redirect(['site/login']);
		}
        if ($model->load(Yii::$app->request->post())){
            $model->attributes = Yii::$app->request->post();
            if($model->validate()){
                $model->login();
                return $this->goBack();
            }
        }
        Url::remember(Yii::$app->request->referrer);
        return $this->render('login', [
            'model' => $model
        ]);
    }
    
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionProfile(){
        $edit = false;
        $member = false;
        if(Yii::$app->request->isAjax){
            $action = Yii::$app->request->get('ajax-action');
            if($action == 'upload-profile-img'){
                if($path = ProfileForm::updateImage(Yii::$app->user->id, $_FILES["ProfileForm"])) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'status' => 'OK',
                        'path' => $path,
                        't' => time()
                    ];
                }
            }
            if($action == 'upload-bg-img'){
                if($path = ProfileForm::updateBgImage(Yii::$app->user->id, $_FILES[0])) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'status' => 'OK',
                        'path' => $path,
                        't' => time()
                    ];
                }
            }
            return false;
        }
        if(!Yii::$app->user->isGuest){
            $user = Yii::$app->user->identity;
            if(User::isVtcMember()) $member = true;
        }else if(!Yii::$app->request->get('id')){
            return $this->redirect(['site/login']);
        }
		$pass_model = new PasswordForm();
		$model = new ProfileForm();
		if(isset($_POST['save_profile_password'])){
			if($pass_model->load(Yii::$app->request->post()) && $pass_model->validate()){
				if($pass_model->editPassword()){
					return $this->redirect(['site/profile']);
				}
			}else{
				$pass_model->addError('password_new', 'Ошибка');
			}
		}
        if(Yii::$app->request->get('action') === 'edit'){
            $model->has_ats = $user->has_ats == '1';
            $model->has_ets = $user->has_ets == '1';
            $model->visible_truckersmp = $user->visible_truckersmp == '1';
            $edit = true;
        }
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            if($model->editProfile()){
                return $this->redirect(['site/profile']);
            }else{
                $model->addError('attribute', 'Возникла ошибка');
            }
        }
        $id = Yii::$app->request->get('id', Yii::$app->user->id);
        if(!$user = User::findOne($id)){
            return $this->goBack();
        }
        $user->age = User::getUserAge($user->birth_date);
        return $this->render($edit ? 'edit_profile' : 'profile', [
            'user' => $user,
            'member' => $member,
            'model' => $model,
			'pass_model' => $pass_model,
			'pass_set' => $user->password
        ]);
    }

    public function actionUsers(){
		if(Yii::$app->user->isGuest){
			return $this->redirect(['site/login']);
		}
        if(User::isAdmin()){
            $query = User::find();
            if(Yii::$app->request->get('q')){
                $q = Yii::$app->request->get('q');
                $query->where(['like', 'first_name', $q])
                    ->orWhere(['like', 'last_name', $q])
                    ->orWhere(['like', 'nickname', $q])
                    ->orWhere(['like', 'company', $q])
                    ->orWhere(['like', 'company', $q]);
            }
            $total = $query->count();
            $pagination = new Pagination([
                'defaultPageSize' => 10,
                'totalCount' => $total
            ]);
            $users = $query->orderBy(['id' => SORT_DESC])->offset($pagination->offset)->limit($pagination->limit)->all();
            return $this->render('users', [
                'users' => $users,
                'currentPage' => Yii::$app->request->get('page', 1),
                'totalPages' => $pagination->getPageCount(),
                'pagination' => $pagination,
                'total' => $total
            ]);
        }else{
            return $this->redirect(['site/error']);
        }
    }

    public function actionReset(){
        if(Yii::$app->request->get('u')) {
            $user = User::findOne(['password_reset' => Yii::$app->request->get('u')]);
            if($user) {
                $model = new ResetForm();
                if($model->load(Yii::$app->request->post()) && $model->validate()) {
                    if($model->saveNewPassword(Yii::$app->request->get('u'))) {
                        return $this->redirect(['site/login']);
                    }
                }
                return $this->render('reset', [
                    'model' => $model
                ]);
            }
        }
        return $this->render('error');
    }

    public static function getRuDate($date){
        if($date != '0000-00-00' && $date != null){
            $month = [
                '01' => 'января',
                '02' => 'февраля',
                '03' => 'марта',
                '04' => 'апреля',
                '05' => 'мая',
                '06' => 'июня',
                '07' => 'июля',
                '08' => 'августа',
                '09' => 'сентября',
                '10' => 'октября',
                '11' => 'ноября',
                '12' => 'декабря'
            ];
            $fdate = new \DateTime($date);
            return $fdate->format('j') . ' ' . $month[$fdate->format('m')] . ' ' . $fdate->format('Y') . 'г.';
        }else{
            return false;
        }
    }

}