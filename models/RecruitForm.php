<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RecruitForm extends Model{

	public $user;
	public $claim;

	// claim
	public $dlc = array();
	public $save_editing = false;
	public $tedit = false;
	public $mods = false;
	public $companies = false;
	public $mic = false;
	public $teamspeak = false;
    public $hear_from;
    public $invited_by;
    public $comment;
    public $reason;
    public $status;
    public $user_id;
    public $viewed;
    public $ets_playtime;
    public $ats_playtime;

	// user
    public $first_name;
    public $last_name;
    public $nickname;
    public $birth_date;
    public $city;
    public $country;
    public $steam;
    public $vk;

    public function __construct($id = null){
		$this->user = User::findOne(Yii::$app->user->id);
		$this->nickname = $this->user->nickname;
		$this->steam = $this->user->steam;
		$this->vk = $this->user->vk;
		$this->first_name = $this->user->first_name;
		$this->last_name = $this->user->last_name;
		$this->birth_date = $this->user->birth_date;
		$this->city = $this->user->city;
		$this->country = $this->user->country;
		if($this->user->steamid){
			$this->ets_playtime = $this->user->has_ets ? Steam::getUserPlayTime($this->user->steamid, '227300') : null;
			$this->ats_playtime = $this->user->has_ats ? Steam::getUserPlayTime($this->user->steamid, '270880') : null;
		}
        if(isset($id)){
            $claim = ClaimsRecruit::find()
				->select([
					'claims_recruit.*',
					'users.*',
					'invited.id as i_id',
					'invited.company as i_company',
					'invited.nickname as i_nickname',
					'admin.first_name as a_first_name',
					'admin.last_name as a_last_name',
				])
				->innerJoin('users', 'users.id = claims_recruit.user_id')
				->leftJoin('users as admin', 'admin.id = claims_recruit.viewed')
				->leftJoin('vtc_members', 'vtc_members.id = claims_recruit.invited_by')
				->leftJoin('users as invited', 'invited.id = vtc_members.user_id')
				->where(['claims_recruit.id' => $id])->one();
			$this->claim = $claim;
			$this->mods = $claim->mods == 1;
			$this->tedit = $claim->tedit == 1;
			$this->save_editing = $claim->save_editing == 1;
			$this->mic = $claim->mic == 1;
            $this->teamspeak = $claim->teamspeak == 1;
            $this->companies = $claim->companies == 1;
            $this->dlc = explode('%', $claim->dlc);
            $this->hear_from = $claim->hear_from;
            $this->ets_playtime = $claim->ets_playtime;
            $this->ats_playtime = $claim->ats_playtime;
            $this->invited_by = $claim->invited_by;
            $this->comment = str_replace("<br />","", $claim->comment);
            $this->reason = $claim->reason;
            $this->status = $claim->status;
            $this->viewed = $claim->viewed;
        }
    }

    public function rules(){
        return [
            [['hear_from', 'comment'], 'string'],
            [['user_id', 'status', 'viewed', 'invited_by', 'ets_playtime', 'ats_playtime'], 'integer'],
			[['mods', 'tedit', 'save_editing', 'mic', 'teamspeak', 'companies'], 'boolean'],
			[['dlc', 'reason'], 'safe'],
            [['steam', 'vk', 'first_name', 'last_name', 'birth_date', 'city', 'country', 'nickname'], 'required', 'message' => 'Заполните все обязательные поля'],
            [['steam'], 'url', 'message' => 'Неверная ссылка Steam', 'defaultScheme' => 'https'],
            [['vk'], 'url', 'message' => 'Неверная ссылка VK', 'defaultScheme' => 'https'],
            [['steam', 'vk', 'first_name', 'last_name', 'birth_date', 'city', 'country', 'nickname'], 'checkUserAttributes']
        ];
    }

    public function checkUserAttributes($attribute, $params){
        if($this->$attribute){
            switch ($attribute){
                case 'nickname' : $this->user->nickname = $this->nickname; break;
                case 'first_name' : $this->user->first_name = $this->first_name; break;
                case 'last_name' : $this->user->last_name = $this->last_name; break;
                case 'birth_date' : $this->user->birth_date = $this->birth_date; break;
                case 'country' : $this->user->country = $this->country; break;
                case 'city' : $this->user->city = $this->city; break;
                case 'vk' : {
                    $regex = '%^(https?:\/\/)?vk.com\/[^\/]*\/?$%';
                    if(!preg_match($regex, $this->vk)){
                        $this->addError($attribute, 'Укажите профиль ВК в виде "<b>http://vk.com/</b><i>ваш_id</i>"');
                        $this->vk = '';
                    }else{
                        $this->user->vk = $this->vk;
                    }
                    break;
                }
                case 'steam' : {
                    if(!$this->validateUrl('steam', $this->steam)){
                        $this->addError($attribute, 'Укажите профиль Steam в виде "<b>http://steamcommunity.com/</b><i>id,profiles</i><b>/</b><i>ваш_id</i>"');
                        $this->steam = '';
                    }else{
                        $this->user->steam = $this->steam;
                        $this->user->steamid = Steam::getUser64ID($this->steam);
                        $this->user->truckersmp = $this->user->steamid ? 'https://truckersmp.com/user/' . TruckersMP::getUserID($this->user->steamid) : null;
                    }
                }
            }
        }
    }

    public static function validateUrl($service, $url){
        switch ($service){
            case 'vk' : $regex = '%^(https?:\/\/)?vk.com\/[^\/]{1,}\/?$%'; break;
            case 'steam' :
            default : $regex = '%^(https?:\/\/)?steamcommunity\.com\/(id|profiles)\/[^\/]{1,}\/?$%'; break;
        }
        return preg_match($regex, $url) ? true : false;
    }

    public function afterValidate() {
		$this->user->update();
    }

    public function addClaim(){
        $claim = new ClaimsRecruit();
        $claim->user_id = Yii::$app->user->id;
		$claim->mods = $this->mods ? '1' : '0';
		$claim->tedit = $this->tedit ? '1' : '0';
		$claim->save_editing = $this->save_editing ? '1' : '0';
		$claim->mic = $this->mic ? '1' : '0';
		$claim->teamspeak = $this->teamspeak ? '1' : '0';
		$claim->companies = $this->companies ? '1' : '0';
		$claim->ets_playtime = $this->ets_playtime;
		$claim->ats_playtime = $this->ats_playtime;
		$claim->dlc = implode('%', $this->dlc);
        $claim->invited_by = $this->invited_by;
        $claim->hear_from = $this->hear_from;
        $claim->comment = nl2br($this->comment);
        $claim->comment = nl2br($this->comment);
        $claim->date = date('Y-m-d');
        Mail::newClaimToAdmin('на вступление', $claim, Yii::$app->user->identity);
        return $claim->save();
    }

    public function editClaim($id){
        $claim = ClaimsRecruit::findOne($id);
		$claim->mods = $this->mods ? '1' : '0';
		$claim->tedit = $this->tedit ? '1' : '0';
		$claim->save_editing = $this->save_editing ? '1' : '0';
		$claim->mic = $this->mic ? '1' : '0';
		$claim->teamspeak = $this->teamspeak ? '1' : '0';
		$claim->companies = $this->companies ? '1' : '0';
		$claim->ets_playtime = $this->ets_playtime;
		$claim->ats_playtime = $this->ats_playtime;
		$claim->dlc = implode('%', $this->dlc);
        $claim->status = $this->status;
		$claim->reason = '';
		$reasons = $claim->getReasonList();
        foreach($this->reason as $item){
			$claim->reason .= key_exists($item, $reasons) ? $reasons[$item] : $item;
			$claim->reason .= ',';
		}
        $claim->invited_by = $this->invited_by;
        $claim->hear_from = $this->hear_from;
        $claim->viewed = $this->viewed;
        $claim->comment = $this->comment;
        if($claim->save()) {
            if(User::isAdmin() && $this->status == '1') {
                $last_member = VtcMembers::find()->orderBy(['sort' => SORT_DESC])->one();
                $member = new VtcMembers();
                $member->user_id = $claim->user_id;
                $member->start_date = date('Y-m-d');
                $member->invited_by = $claim->invited_by;
                $member->sort = ($last_member ? intval($last_member->sort) : 0)+1;
                $user = User::findOne($claim->user_id);
                $user->company = 'Volvo Trucks';
                $user->save();
                Notifications::addNotification('Вы были приняты в ряды водителей ВТК', $this->user_id);
                return $member->save();
            }else {
                return true;
            }
        }else{
            return false;
        }
    }

    public static function quickClaimApply($id){
        $claim = ClaimsRecruit::findOne($id);
        $claim->viewed = Yii::$app->user->id;
        $claim->status = '1';
        if($claim->save()) {
            $last_member = VtcMembers::find()->orderBy(['sort' => SORT_DESC])->one();
            $member = new VtcMembers();
            $member->user_id = $claim->user_id;
			$member->invited_by = $claim->invited_by;
            $member->start_date = date('Y-m-d');
            $member->sort = ($last_member ? intval($last_member->sort) : 0)+1;
            $user = User::findOne($claim->user_id);
            $user->company = 'Volvo Trucks';
            $user->save();
            Notifications::addNotification('Вы были приняты в ряды водителей ВТК', $claim->user_id);
            return $member->save();
        }else{
            return false;
        }
    }

    public static function deleteClaim($id){
        $claim = ClaimsRecruit::findOne($id);
        return $claim->delete() == 1;
    }

    public function attributeLabels() {
        return [
            'hear_from' => 'Как вы узнали про ВТК Volvo Trucks?',
            'invited_by' => 'Кто Вас пригласил в ВТК Volvo Trucks?',
            'comment' => 'Ваш комментарий к заявке',
            'reason' => 'Причина (если отказ)',
            'status' => 'Статус заявки',
        ];
    }

}