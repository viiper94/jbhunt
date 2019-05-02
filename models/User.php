<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface{

    public $age;

    public $member_id;
    public $step4_complete;

    public $notifications = null;
    public $has_unread_notifications = false;

    public static function tableName() {
        return 'users';
    }

    public static function findIdentity($id){
    	$user = self::find()
			->select(['users.*', 'vtc_members.id as member_id', 'vtc_members.step4_complete'])
			->leftJoin('vtc_members', 'users.id = vtc_members.user_id')
			->where(['users.id' => $id])->one();
    	$user->setUserActivity();
    	$user->getUserNotifications();
        return $user;
    }

	public function getUserNotifications(){
		$this->notifications = Notifications::find()
			->where(['uid' => $this->id])
			->orderBy(['date' => SORT_DESC, 'status' => SORT_ASC])
			->all();
		foreach ($this->notifications as $notification){
			if($notification->status == '0') {
				$this->has_unread_notifications = true;
				break;
			}
		}
    }

    public static function findIdentityByAccessToken($token, $type = null){

}

    public static function findByUsername($username){
        return User::findOne(['username' => $username]);
    }

    public static function findBySteamId($steamid){
        return User::findOne(['steamid' => $steamid]);
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        return $this->auth_key;
    }

    public function validateAuthKey($authKey){
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password){
        return password_verify($password, $this->password);
    }

	public static function loginBySteamId($json){
		if($user = User::findBySteamId($json->steamid)){
			return Yii::$app->user->login($user, 3600*24*30);
		}else{
			$user = new User();
			$user->username = !User::findByUsername(explode('/', $json->profileurl)[4]) ?
				explode('/', $json->profileurl)[4] :
				$json->steamid;
			$user->email = $user->username.'@volvovtc.com';
			if(property_exists($json, 'realname')){
				$user->first_name = explode(' ', $json->realname)[0];
				$user->last_name = explode(' ', $json->realname)[1];
			}
			$url = $json->avatarfull;
			$img = $_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/'.$json->steamid.'.jpg';
			file_put_contents($img, Steam::getData($url));
			$user->picture = $json->steamid.'.jpg';
			$tr_id = TruckersMP::getUserID($json->steamid);
			$user->truckersmp = $tr_id ? 'https://truckersmp.com/user/'.$tr_id : null;
			$user->steamid = $json->steamid;
			$user->steam = $json->profileurl;
			if($games = Steam::getUsersGames($json->steamid)){
				foreach($games as $game){
					if($game->appid == '227300') $user->has_ets = '1';
					if($game->appid == '270880') $user->has_ats = '1';
				}
			}
			$user->nickname = $json->personaname;
			$user->social = 'steam';
			$user->auth_key = Yii::$app->security->generateRandomString();
			$user->registered = date('Y-m-d');
			if($user->save()){
				Mail::newUserToAdmin($user);
				Yii::$app->user->login($user, 3600*24*30);
				return true;
			}else{
				return false;
			}
		}
    }

    public static function getUserAge($birth_date){
        //var_dump($birth_date);
        if($birth_date != '0000-00-00' && $birth_date != null && $birth_date != ''){
            $birthday_timestamp = strtotime($birth_date);
            $age = date('Y') - date('Y', $birthday_timestamp);
            if(date('md', $birthday_timestamp) > date('md')) $age--;
            $last_number = $age % 10;
            if(($last_number > 0 && $last_number < 5) && ($age > 20 || $age < 10)){
                if(($last_number == 1) && ($age > 20 || $age < 10)) $let = $age . ' год';
                else $let = $age . ' года';
            }
            else $let = $age . ' лет';
            return $let;
        }else{
            return false;
        }
    }

    public static function isAdmin(){
        return !Yii::$app->user->isGuest && Yii::$app->user->identity->admin == '1';
    }

    public static function isVtcMember($id = null){
        if(isset($id)){
			$is_member = User::find()
				->select(['users.id as uid', 'vtc_members.id as mid'])
				->innerJoin('vtc_members', 'users.id = vtc_members.user_id')
				->where(['users.id' => $id])->count() !== '0';
        }else {
            if(Yii::$app->user->isGuest) {
                $is_member = false;
            } else {
                $is_member = Yii::$app->user->identity->member_id ? true : false;
            }
        }
        return $is_member;
    }

	public static function canCreateConvoy(){
    	$can = false;
    	if(self::isAdmin()) return true;
    	if(!Yii::$app->user->isGuest && $member =  VtcMembers::find()->where(['user_id' => Yii::$app->user->id])->one()){
			if($member->can_lead == '1' && $member->post_id >= 3 && $member->scores_total > 0 && !$member->vacation){
				$can = true;
			}
		}
		return $can;
    }

    public static function generatePasswordResetString($email){
        $user = User::findOne(['email' => $email]);
        if($user){
            $user->password_reset = Yii::$app->security->generateRandomString(64);
            if($user->update() !== false){
                return $user->password_reset;
            }
        }
        return false;
    }

    public function setUserActivity(){
        $this->last_active = date('Y-m-d H:i');
        $this->update();
    }

    public static function isOnline($user){
    	if(is_numeric($user)){
    		$user = User::findOne($user);
		}
        if($user->last_active){
            $last_active = strtotime($user->last_active);
            if(time() - $last_active <= 120) return true;
        }
        return false;
    }

}