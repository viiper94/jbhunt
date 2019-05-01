<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class MemberForm extends Model{

    public $id;
    public $vacation;
    public $vacation_undefined = false;
    public $can_lead = false;
    public $can_lead_open = false;
    public $can_center = false;
    public $can_close = false;
    public $scores_total;
    public $scores_month;
    public $scores_other;
    public $exam_driving = false;
    public $exam_3_cat = false;
    public $exam_2_cat = false;
    public $exam_1_cat = false;
    public $additional;
    public $post_id;
    public $start_date;
    public $notify = array();

    public $vk;
    public $steam;
    public $first_name;
    public $last_name;
    public $birth_date;
    public $nickname;
	public $achievements = array();

	public $member;

    public function __construct($id = null){
        if(isset($id)){
        	$member = VtcMembers::find()->select([
					'users.*',
					'vtc_members.*',
					'm_invited.user_id as i_uid',
					'u_invited.nickname as i_nickname',
				])
				->innerJoin('users', 'users.id = vtc_members.user_id')
				->leftJoin('vtc_members as m_invited', 'm_invited.id = vtc_members.invited_by')
				->leftJoin('users as u_invited', 'u_invited.id = m_invited.user_id')
				->where(['vtc_members.id' => $id])->one();
        	$this->member = $member;
            $this->vacation = $member->vacation;
			$this->achievements = array();
			if($member->achievements){
				foreach (unserialize($member->achievements) as $achievement){
					$this->achievements[$achievement] = true;
				}
			}
            $this->vacation_undefined = $member->vacation_undefined == '1';
            $this->can_lead = $member->can_lead == '1';
            $this->can_lead_open = $member->can_lead_open == '1';
            $this->can_center = $member->can_center == '1';
            $this->can_close = $member->can_close == '1';
            $this->scores_total = $member->scores_total;
            $this->scores_month = $member->scores_month;
            $this->scores_other = $member->scores_other;
            $this->exam_driving = $member->exam_driving == '1';
            $this->exam_3_cat = $member->exam_3_cat == '1';
            $this->exam_2_cat = $member->exam_2_cat == '1';
            $this->exam_1_cat = $member->exam_1_cat == '1';
            $this->additional = $member->additional;
            $this->post_id = $member->post_id;
            $this->start_date = $member->start_date;
            $this->vk = $member->vk;
            $this->steam = $member->steam;
            $this->first_name = $member->first_name;
            $this->last_name = $member->last_name;
            $this->birth_date = $member->birth_date;
            $this->nickname = $member->getMemberNickname();
        }
    }

    public function rules(){
        return [
            [['can_lead', 'can_lead_open', 'can_center', 'can_close', 'scores_total', 'scores_month', 'scores_other',
                'exam_driving', 'exam_3_cat', 'exam_2_cat', 'exam_1_cat', 'post_id'], 'integer'],
            [['vacation', 'start_date', 'achievements'], 'safe'],
            [['vacation_undefined'], 'boolean'],
            [['additional', 'vk', 'steam', 'first_name', 'last_name', 'nickname', 'birth_date'], 'string', 'max' => 1024],
            [['notify'], function($attribute, $params){
                if(!is_array($this->notify)) $this->addError('Что то пошло не так =(');
            }]
        ];
    }

    public function attributeLabels(){
        return [
            'vacation' => 'Отпуск',
            'vacation_undefined' => 'Неопределенный срок',
            'can_lead' => 'Ведущий',
            'can_lead_open' => 'Ведущий открытого конвоя',
            'can_center' => 'Центральный',
            'can_close' => 'Замыкающий',
            'scores_total' => 'Всего баллов',
            'scores_month' => 'Баллов за месяц',
            'scores_other' => 'Другое',
            'exam_driving' => 'Вождение',
            'exam_3_cat' => 'Экзамен на 3 категорию',
            'exam_2_cat' => 'Экзамен на 2 категорию',
            'exam_1_cat' => 'Экзамен на 1 категорию',
            'additional' => 'Дополнительно',
            'post_id' => 'Должность',
            'start_date' => 'Дата вступления',
            'notify' => 'Уведомить сотрудника о',
            'vk' => 'Профиль ВК',
            'steam' => 'Профиль в Steam',
            'truckersmp' => 'Профиль в TruckersMP',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'birth_date' => 'Дата рождения',
            'nickname' => 'Никнейм',
        ];
    }

    public function editMember($id){
        $member = VtcMembers::findOne($id);
        $user = User::findOne($member->user_id);
        $member->vacation = $this->vacation;
        $member->vacation_undefined = $this->vacation_undefined ? '1' : '0';
        $member->can_lead = $this->can_lead ? '1' : '0';
        $member->can_lead_open = $this->can_lead_open ? '1' : '0';
        $member->can_center = $this->can_center ? '1' : '0';
        $member->can_close = $this->can_close ? '1' : '0';
		if($member->scores_total != $this->scores_total){
			$member->scores_updated = date('Y-m-d').' '.(intval(date('H')) + 2) .':'.date('i');
			$member->scores_history = VtcMembers::setScoresHistory($member->scores_history, [
                'total' => $this->scores_total,
                'month' => $this->scores_month,
                'other' => $this->scores_other
			]);
		}
		$member->scores_total = $this->scores_total;
		$member->scores_month = $this->scores_month;
        $member->scores_other = $this->scores_other;
        $member->additional = $this->additional ? $this->additional : VtcMembers::updateAdditionalByScores($member);
        $member->exam_driving = $this->exam_driving ? '1' : '0';
        $member->exam_3_cat = $this->exam_3_cat ? '1' : '0';
        $member->exam_2_cat = $this->exam_2_cat ? '1' : '0';
        $member->exam_1_cat = $this->exam_1_cat ? '1' : '0';
        $member->post_id = $this->post_id;
        $member->start_date = $this->start_date;
        $user->vk = $this->vk;
        $user->steam = $this->steam;
        $user->steamid = Steam::getUser64ID($user->steam);
        $user->truckersmp = $user->steamid ? 'https://truckersmp.com/user/' . TruckersMP::getUserID($user->steamid) : null;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->birth_date = $this->birth_date;
        $user->nickname = $this->nickname;
        $achievements = null;
        foreach($this->achievements as $id => $achievement){
            if($achievement) $achievements[] = $id;
        }
        if($achievements) $user->achievements = serialize($achievements);
        if($member->update() !== false && $user->update() !== false){
            foreach ($this->notify as $key => $value){
                if($value != '0' && $value != ''){
                    switch ($key){
                        case 'increase' : $text = 'Вас было повышено в должности!'; break;
                        case 'decrease' : $text = 'Вас было понижено в должности!'; break;
                        case 'scores+' : $text = 'Вам было начислено баллы!'; break;
                        case 'scores-' : $text = 'Вам было списано баллы!'; break;
                        case 'ability' : $text = 'Ваши возможности были изменены!'; break;
                        case 'profile' : $text = 'Ваш профиль был изменен администрацией!'; break;
                        case 'custom' :
                        default: $text = $this->notify['custom']; break;
                    }
                    Notifications::addNotification($text, $member->user_id);
                }
            }
            return true;
        }else{
        	return false;
        }
    }
}