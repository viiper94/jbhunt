<?php

namespace app\models;

use Yii;
use yii\base\Model;

class FiredForm extends Model{

	public $user;
	public $claim;

    public $id;
    public $reason;
    public $status;
    public $member_id;
    public $user_id;
    public $viewed;

    public function __construct($id = null){
        if(isset($id)){
			$claim = ClaimsFired::find()
				->select([
					'claims_fired.*',
					'users.*',
					'admin.first_name as a_first_name',
					'admin.last_name as a_last_name',
					'vtc_members.id as v_member_id'
				])
				->innerJoin('users', 'users.id = claims_fired.user_id')
				->leftJoin('users as admin', 'admin.id = claims_fired.viewed')
				->leftJoin('vtc_members', 'vtc_members.id = claims_fired.member_id')
				->where(['claims_fired.id'=> $id])->one();
			$this->claim = $claim;
            $this->member_id = $this->claim->member_id;
            $this->user_id = $this->claim->user_id;
            $this->reason = $this->claim->reason;
            $this->status = $this->claim->status;
            $this->viewed = $this->claim->viewed;
        }
    }

    public function rules(){
        return [
            [['reason'], 'string'],
            [['member_id', 'user_id', 'status', 'viewed'], 'integer'],
        ];
    }

    public function addClaim(){
        $claim = new ClaimsFired();
        $user = VtcMembers::find()->select(['id'])->where(['user_id' => Yii::$app->user->id])->one();
        $claim->member_id = $user->id;
        $claim->user_id = Yii::$app->user->id;
        $claim->reason = nl2br($this->reason);
        $claim->date = date('Y-m-d');
        Mail::newClaimToAdmin('на увольнение', $claim, Yii::$app->user->identity);
        return $claim->save();
    }

    public function editClaim($id) {
        $claim = ClaimsFired::findOne($id);
        $claim->status = $this->status;
        $claim->reason = $this->reason;
        $claim->viewed = $this->viewed;
        if($claim->save()) {
            if($this->status == '1') {
                $member = VtcMembers::findOne($claim->member_id);
                $user = User::findOne($claim->user_id);
                $user->company = '';
                $user->save();
                Notifications::addNotification('Ваше заявление на увольнение было одобрено', $this->user_id);
                return $member->delete() == 1;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public static function quickClaimApply($id){
        $claim = ClaimsFired::findOne($id);
        $claim->status = '1';
        $claim->viewed = Yii::$app->user->id;
        if($claim->save()) {
            $member = VtcMembers::findOne($claim->member_id);
            $user = User::findOne($claim->user_id);
            $user->company = '';
            $user->save();
            Notifications::addNotification('Ваше заявление на увольнение было одобрено', $claim->user_id);
            return $member->delete() == 1;
        }else{
            return false;
        }
    }

    public static function deleteClaim($id){
        $claim = ClaimsFired::findOne($id);
        return $claim->delete() == 1;
    }

    public function attributeLabels() {
        return [
            'reason' => 'Причина увольнения',
            'status' => 'Статус заявки',
        ];
    }

}