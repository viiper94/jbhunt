<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class ProfileForm extends Model{

    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $birth_date;
    public $country;
    public $city;
    public $vk;
    public $steam;
    public $steamid64;
    public $visible_truckersmp;
    public $visible_steam = true;
    public $truckersmp;
    public $has_ats;
    public $has_ets;
    public $nickname;
    public $picture;
    public $bg_image;

    public function rules() {
        return [
            [['username', 'email'], 'required', 'message' => 'Обязательное поле'],
            [['username', 'email', 'steam', 'vk'], 'trim'],
            [['username'], 'checkUsername'],
            [['email'], 'checkEmail'],
            [['has_ats', 'has_ets'], 'boolean'],
            [['email'], 'email', 'message' => 'Неправильный E-Mail'],
            [['vk', 'steam', 'truckersmp'], 'url', 'defaultScheme' => 'https', 'message' => 'Неправильная ссылка'],
            [['steam'], 'checkSteam'],
            [['vk'], 'checkVk'],
            [['first_name', 'last_name', 'country', 'city', 'birth_date', 'nickname'], 'string']
        ];
    }

    public function checkSteam($attribute, $params) {
        if($this->steam){
            $regex = '%^(https?:\/\/)?steamcommunity\.com\/(id|profiles)\/[^\/]{1,}\/?$%';
            if(!preg_match($regex, $this->steam)){
                $this->addError($attribute, 'Укажите профиль Steam в виде "<b>http://steamcommunity.com/</b><i>id,profiles</i><b>/</b><i>ваш_id</i>"');
            }
        }
    }

    public function checkVk($attribute, $params) {
        if($this->vk){
            $regex = '%^(https?:\/\/)?vk.com\/[^\/]{1,}\/?$%';
            if(!preg_match($regex, $this->vk)){
                $this->addError($attribute, 'Укажите профиль ВК в виде "<b>http://vk.com/</b><i>ваш_id</i>"');
            }
        }
    }

    public function checkUsername($attribute, $params){
        $user = User::find()->where(['username' => $this->username])->andWhere(['!=', 'id', Yii::$app->user->identity->id])->all();
        if(count($user) > 0){
            $this->addError($attribute, 'Такой логин уже зарегистрирован');
        }
    }

    public function checkEmail($attribute, $params){
        $user = User::find()->where(['email' => $this->email])->andWhere(['!=', 'id', Yii::$app->user->identity->id])->all();
        if(count($user) > 0){
            $this->addError($attribute, 'Такой E-Mail уже зарегистрирован');
        }
    }

    public function editProfile(){
        $errors = array();
        $user = User::findIdentity(Yii::$app->user->identity->id);
        $form = Yii::$app->request->post('ProfileForm');
		$user->username = $form['username'];
		$user->email = $form['email'];
		$user->has_ats = $form['has_ats'];
		$user->has_ets = $form['has_ets'];
		$user->vk = $form['vk'];
		$user->steam = $form['steam'];
		if($form['steam'] != ''){
			$user->steamid = Steam::getUser64ID($form['steam']);
			$tr_id = TruckersMP::getUserID($user->steamid);
			$user->truckersmp = $tr_id ? 'https://truckersmp.com/user/' . $tr_id : null;
			$user->visible_truckersmp = $form['visible_truckersmp'];
		}
		$user->visible_steam = $form['visible_steam'];
		$user->truckersmp = $form['truckersmp'];
		$user->first_name = $form['first_name'];
		$user->last_name = $form['last_name'];
		$user->country = $form['country'];
		$user->city = $form['city'];
		$user->birth_date = $form['birth_date'];
		$user->nickname = $form['nickname'];
		if($image = UploadedFile::getInstance($this, 'picture')){
			if($user->picture !== 'default.jpg'){
				unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/'.$user->picture);
			}
			$user->picture = $user->id.'.'.$image->extension;
			$form['picture'] = $user->picture;
			Yii::$app->user->identity->picture = $user->picture;
			$image->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/'.$user->picture);
		}
		if($image = UploadedFile::getInstance($this, 'bg_image')){
			if($user->bg_image !== 'default.jpg'){
				unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/bg/'.$user->bg_image);
			}
			$user->bg_image = $user->id.'.'.$image->extension;
			$form['picture'] = $user->bg_image;
			Yii::$app->user->identity->bg_image = $user->bg_image;
			$image->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/bg/'.$user->bg_image);
		}
		$this->updateIdentity($form);

        return $user->update() !== false ? true : false;

    }

    private function updateIdentity($data){
        Yii::$app->user->identity->username = $data['username'];
        Yii::$app->user->identity->email = $data['email'];
        Yii::$app->user->identity->has_ats = $data['has_ats'];
        Yii::$app->user->identity->has_ets = $data['has_ets'];
        Yii::$app->user->identity->vk = $data['vk'];
        Yii::$app->user->identity->steam = $data['steam'];
        Yii::$app->user->identity->steamid = $data['steamid64'];
        Yii::$app->user->identity->truckersmp = $data['truckersmp'];
        Yii::$app->user->identity->visible_truckersmp = $data['visible_truckersmp'];
        Yii::$app->user->identity->visible_steam = $data['visible_steam'];
        Yii::$app->user->identity->first_name = $data['first_name'];
        Yii::$app->user->identity->last_name = $data['last_name'];
        Yii::$app->user->identity->country = $data['country'];
        Yii::$app->user->identity->city = $data['city'];
        Yii::$app->user->identity->birth_date = $data['birth_date'];
        Yii::$app->user->identity->nickname = $data['nickname'];
    }

    public static function updateImage($id, $file) {
        $user = User::findOne($id);
        if($user->picture != 'default.jpg') unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/' . $user->picture);
        switch ($file['type']){
            case 'image/png': $ext = '.png'; break;
            case 'image/gif': $ext = '.gif'; break;
            default: $ext = '.jpg';
        }
        $user->picture = $user->id . $ext;
        $user->update();
        $dir = $_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/' . $user->picture;
        $path = false;
        if(move_uploaded_file($file['tmp_name'], $dir)){
            $path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir);
        }
        return $path;
    }

    public static function updateBgImage($id, $file) {
        $user = User::findOne($id);
        if($user->bg_image != 'default.jpg' && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/bg/'.$user->bg_image)){
        	unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/bg/'.$user->bg_image);
		}
        switch ($file['type']){
            case 'image/png': $ext = '.png'; break;
            default: $ext = '.jpg';
        }
		if($file['size'] > 1500000){
			$img = new Image();
			$img->load($file['tmp_name']);
			if($img->getWidth() > 1920){
				$img->resizeToWidth(1920);
			}
			$img->save($file['tmp_name']);
		}
        $user->bg_image = $user->id. $file['name'] . $ext;
        $user->update();
        $dir = $_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/users/bg/' . $user->bg_image;
        $path = false;
        if(move_uploaded_file($file['tmp_name'], $dir)){
            $path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir);
        }
        return $path;
    }

}