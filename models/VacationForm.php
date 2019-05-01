<?php

namespace app\models;

use Yii;
use yii\base\Model;

class VacationForm extends Model{

	public $user;
	public $claim;

    public $to_date;
    public $reason;
    public $status;
    public $member_id;
    public $user_id;
    public $viewed;
    public $vacation_undefined = false;

    public function __construct($id = null){
        if(isset($id)){
            $claim = ClaimsVacation::find()
				->select([
					'users.*',
					'users.id as uid',
					'claims_vacation.*',
					'admin.first_name as a_first_name',
					'admin.last_name as a_last_name',
					'vtc_members.id as v_member_id'
				])
				->innerJoin('users', 'users.id = claims_vacation.user_id')
				->leftJoin('users as admin', 'admin.id = claims_vacation.viewed')
				->leftJoin('vtc_members', 'vtc_members.id = claims_vacation.member_id')
				->where(['claims_vacation.id' => $id])->one();
			$this->claim = $claim;
            $this->member_id = $claim->member_id;
            $this->user_id = $claim->user_id;
            $this->to_date = $claim->to_date;
            $this->reason = $claim->reason;
            $this->status = $claim->status;
            $this->viewed = $claim->viewed;
            $this->vacation_undefined = $claim->vacation_undefined == '1';
        }
    }

    public function rules(){
        return [
            [['to_date'], 'required'],
            [['vacation_undefined'], 'boolean'],
            [['reason', 'to_date'], 'string'],
            [['member_id', 'user_id', 'status', 'viewed'], 'integer'],
        ];
    }

    public function addClaim(){
        $claim = new ClaimsVacation();
        $member = VtcMembers::find()->select(['id'])->where(['user_id' => Yii::$app->user->id])->one();
        $claim->member_id = $member->id;
        $claim->user_id = Yii::$app->user->id;
        if($this->vacation_undefined == '0'){
            $claim->to_date = $this->to_date;
        }
        $claim->vacation_undefined = $this->vacation_undefined == '1' ? 1 : 0;
        $claim->date = date('Y-m-d');
        Mail::newClaimToAdmin('на отпуск', $claim, Yii::$app->user->identity);
        return $claim->save();
    }

    public function editClaim($id){
        $claim = ClaimsVacation::findOne($id);
        $claim->status = $this->status;
        $claim->reason = $this->reason;
        $claim->to_date = $this->to_date;
        $claim->viewed = $this->viewed;
        $claim->vacation_undefined = $this->vacation_undefined ? '1' : '0';
        if($claim->save()){
            if($this->status == '1'){
                $member = VtcMembers::find()->where(['id' => $claim->member_id])->one();
                $member->vacation = $this->to_date;
                $member->vacation_undefined = $this->vacation_undefined ? 1 : 0;
                Notifications::addNotification('Ваше заявление на отпуск было одобрено', $member->user_id);
                return $member->save();
            }else {
                return true;
            }
        }else{
            return false;
        }
    }

    public static function quickClaimApply($id){
        $claim = ClaimsVacation::findOne($id);
        $claim->status = '1';
        $claim->viewed = Yii::$app->user->id;
        if($claim->save()) {
            $member = VtcMembers::find()->where(['id' => $claim->member_id])->one();
            $member->vacation = $claim->to_date;
            $member->vacation_undefined = $claim->vacation_undefined ? 1 : 0;
            Notifications::addNotification('Ваше заявление на отпуск было одобрено', $member->user_id);
            return $member->save();
        }else{
            return false;
        }
    }

    public static function deleteClaim($id){
        $claim = ClaimsVacation::findOne($id);
        return $claim->delete() == 1;
    }

    public function attributeLabels() {
        return [
            'to_date' => 'До какой даты отпуск?',
            'reason' => 'Причина (если отказ)',
            'status' => 'Статус заявки',
        ];
    }

}