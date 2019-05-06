<?php

namespace app\models;

use DateTime;
use Yii;
use yii\db\ActiveRecord;

class VtcMembers extends ActiveRecord{

	public $first_name;
	public $last_name;
	public $birth_date;
	public $nickname;
	public $company;
	public $picture;
	public $vk;
	public $steam;
	public $steamid;
	public $truckersmp;
	public $visible_truckersmp;
	public $visible_steam;
	public $achievements;
	public $last_active;

	public $post_name;
	public $post_admin;

	public $i_id;
	public $i_nickname;

    public $banned = false;

    public static function tableName(){
        return 'vtc_members';
    }

    public function rules(){
        return [
            [['user_id'], 'required'],
            [['user_id', 'can_lead', 'can_lead_open', 'can_center', 'can_close', 'scores_total', 'scores_month', 'scores_other',
                'exam_driving', 'exam_3_cat', 'exam_2_cat', 'exam_1_cat', 'post_id', 'vacation_undefined', 'sort'], 'integer'],
            [['vacation', 'start_date'], 'safe'],
            [['additional'], 'string', 'max' => 1024],
            [['scores_history'], 'string', 'max' => 4096],
            [['scores_updated'], 'safe'],
            [['user_id'], 'unique'],
        ];
    }

    public static function getMembers($get_bans = false){
        $members = array();
        $all_members = VtcMembers::find()
			->select(['users.*', 'vtc_members.*', 'vtc_positions.name as post_name', 'vtc_positions.admin as post_admin'])
			->innerJoin('users', 'users.id = vtc_members.user_id')
			->leftJoin('vtc_positions', 'vtc_positions.id = vtc_members.post_id')
			->orderBy('post_id DESC, `scores_month` + `scores_other` DESC, scores_total DESC, start_date')->all();
        foreach($all_members as $member){
                if($member->truckersmp != '' && $get_bans){
                    $member->banned = TruckersMP::isMemberBanned($member->user_id->truckersmp);
                }
                if($member->post_admin == '1') $members['Администрация'][] = $member;
                else $members[$member->post_name][] = $member;
		}
        return $members;
    }

    public static function getAllMembers($order_by_sort = true){
        $members =  VtcMembers::find()
			->select(['vtc_members.*', 'users.*', 'vtc_members.id as id', 'vtc_positions.name as post_name'])
			->innerJoin('users', 'vtc_members.user_id = users.id')
            ->leftJoin('vtc_positions', 'vtc_positions.id = vtc_members.post_id');
        if($order_by_sort) $members = $members->orderBy(['sort' => SORT_ASC, 'start_date' => SORT_DESC]);
        $members = $members->all();
        return $members;
    }

    public static function getMembersArray(){
        $all_members = self::getAllMembers(false);
        $members = array();
        foreach ($all_members as $member){
            $members[$member->id] = '[J.B. Hunt] '.$member->nickname;
        }
        return $members;
    }

    public static function fireMember($id){
        $member = VtcMembers::findOne($id);
        $user = User::findOne($member->user_id);
        $user->company = '';
        $user->save();
        return $member->delete() !== false;
    }

    public static function addScores($id, $scores, $target){
        $member = VtcMembers::findOne($id);
        if($target == 'month'){
            $member->scores_month = intval($member->scores_month) + intval($scores);
            $member->scores_total = intval($member->scores_total) + intval($scores);
        }elseif($target = 'other'){
            $member->scores_other = intval($member->scores_other) + intval($scores);
            $member->scores_total = intval($member->scores_total) + intval($scores);
        }
        $member->additional = self::updateAdditionalByScores($member);
        $member->scores_updated = date('Y-m-d ').(intval(date('H')) + 2).date(':i');
        $member->scores_history = self::setScoresHistory($member->scores_history, ['total' => $member->scores_total, 'month' => $member->scores_month, 'other' => $member->scores_other]);
        if($member->update() !== false){
            Notifications::addNotification('Вам было начислено '. $scores . ' баллов!', $member->user_id);
            return ['other' => $member->scores_other, 'month' => $member->scores_month, 'total' => $member->scores_total, 'updated' => date('d.m.y H:i')];
        }
        return false;
    }

    public static function updateAdditionalByScores($member){
        $additional = '';
        if($member->post_id == '2' && $member->scores_total >= 50 && $member->exam_3_cat == '0') $additional = 'На 3 категорию';
        if(($member->post_id == '3' || $member->post_id == '2') && $member->scores_total >= 200 && $member->exam_2_cat == '0') $additional = 'На 2 категорию';
        if(($member->post_id == '4' || $member->post_id == '3' || $member->post_id == '2') && $member->scores_total >= 400 && $member->exam_1_cat == '0') $additional = 'На 1 категорию';
        return $additional;
    }

	public function hasVacation(){
		$vacation = new \DateTime($this->vacation);
		$now = new \DateTime();
		return $vacation > $now;
    }

    public static function cleanVacations(){
        $members = VtcMembers::find()->where(['!=', 'vacation', ''])->all();
        foreach($members as $member){
            $vacation = new \DateTime($member->vacation);
            $now = new \DateTime();
            if($vacation < $now){
                $member->vacation = '';
                $member->save();
            }
        }
    }

    public static function zeroScores(){
        $members = VtcMembers::find()->all();
        foreach($members as $member){
            $member->scores_other = 0;
            $member->scores_month = 0;
            $member->update();
        }
    }

    public static function getBans($steamid64){
        $bans = array();
        foreach ($steamid64 as $uid => $steamid){
            $bans[$uid] = TruckersMP::isMemberBanned($steamid);
        }
        return $bans;
    }

    public function getMemberNickname(){
        $truckersmp = TruckersMP::getMemberTruckersMpNickname($this->steamid);
        $steam = Steam::getPlayerNickname($this->steamid);
        if(strpos($truckersmp, '[J.B. Hunt]') !== false){
            return str_replace(['[J.B. Hunt]', '[J.B. Hunt] '], '', $truckersmp);
        }else if(strpos($steam, '[J.B. Hunt]') !== false){
            return str_replace(['[J.B. Hunt]', '[J.B. Hunt] '], '', $steam);
        }else{
            return $this->nickname;
        }
    }

    public function getMemberDays(){
        $datetime1 = new DateTime($this->start_date);
        $datetime2 = new DateTime();
        $days = intval($datetime1->diff($datetime2)->format('%a'));
        if($days == 1) $days .= ' день';
        else if($days > 1 && $days < 5) $days .= ' дня';
        else if($days == 0 || $days >= 5 && $days < 21) $days .= ' дней';
        else if($days > 20){
            $last_digit = $days > 100 ? $days % 100 : $days % 10;
            if($last_digit == 1) $days .= ' день';
            else if($last_digit > 1 && $last_digit < 5) $days .= ' дня';
            else if($last_digit == 0 || $last_digit >= 5 && $last_digit < 21) $days .= ' дней';
            else if($last_digit > 20){
                $last_digit = $last_digit % 10;
                if($last_digit == 1) $days .= ' день';
                else if($last_digit > 1 && $last_digit < 5) $days .= ' дня';
                else if($last_digit == 0 || $last_digit >= 5) $days .= ' дней';
            }
        }
        return $days;
    }

    public static function setScoresHistory($scores_history, $scores){
        $new_score['date'] = date('Y-m-d H:i');
        $new_score['total'] = $scores['total'];
        $new_score['month'] = $scores['month'];
        $new_score['other'] = $scores['other'];
        $new_score['admin'] = Yii::$app->user->id;
        if($scores_history){
            $member_scores = unserialize($scores_history);
            if(count($member_scores) >= 20){
                $member_scores = array_slice($member_scores, 0, 19);
            }
            array_unshift($member_scores, $new_score);
            $scores_history = serialize($member_scores);
        }else{
            $scores_history = serialize([$new_score]);
        }
        return $scores_history;
    }

    public static function resortMembers($id){
        $member = VtcMembers::findOne($id);
        $member_2 = VtcMembers::find();
        if(Yii::$app->request->get('dir') === 'down'){
            $member_2 = $member_2->where(['>', 'sort', $member->sort])->orderBy(['sort' => SORT_ASC]);
        }elseif(Yii::$app->request->get('dir') === 'up'){
            $member_2 = $member_2->where(['<', 'sort', $member->sort])->orderBy(['sort' => SORT_DESC]);
        }
        $member_2 = $member_2->one();
        if($member_2 == null) return true;
        $memberSort_2 = $member_2->sort;
        $sortTmp = $memberSort_2;
        $memberSort_2 = $member->sort;
        $member->sort = $sortTmp;
        $member_2->sort = $memberSort_2;
        return $member_2->update() == 1 && $member->update() == 1 ? true : false;
    }

    public static function isCompleteStep4(){
        return Yii::$app->user->identity->step4_complete == '1';
    }

    public static function step4Complete($id){
        $member = VtcMembers::findOne(['user_id' => $id]);
        $member->step4_complete = 1;
        return $member->update() == 1;
    }

}