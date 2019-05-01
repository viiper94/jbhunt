<?php

namespace app\models;

use yii\base\Model;

class AppealForm extends Model{

    public $uid;
    public $is_anonymous = false;
    public $appeal_to_id;
    public $description;
    public $proof;

    public function rules(){
        return [
            [['appeal_to_id'], 'required', 'message' => 'Вы не выбрали сотрудника'],
            [['appeal_to_id'], 'integer'],
            [['is_anonymous'], 'boolean'],
            [['description', 'proof'], 'string', 'max' => 2048],
        ];
    }

    public function attributeLabels(){
        return [
            'is_anonymous' => 'Анонимная жалоба',
            'appeal_to_id' => 'На какого сотрудника жалоба',
            'description' => 'Описание',
            'proof' => 'Доказательства',
        ];
    }

    public function addAppeal(){
        $appeal = new Appeals();
        $member = VtcMembers::findOne($this->appeal_to_id);
        $appeal->appeal_to_id = $this->appeal_to_id;
        $appeal->appeal_to_user_id = $member->user_id;
        $appeal->description = nl2br($this->description);
        $appeal->proof = nl2br($this->proof);
        $appeal->date = date('Y-m-d H:i');
        $appeal->is_anonymous = $this->is_anonymous ? '1' : '0' ;
        $appeal->uid = !\Yii::$app->user->isGuest ? \Yii::$app->user->id : null;
        if($appeal->save()){
            Mail::newAppeal($appeal, $member->user_id);
            return true;
        }
    }

}