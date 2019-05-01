<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Other extends ActiveRecord{

    public function rules(){
        return [
            [['date'], 'safe'],
            [['category'], 'string', 'max' => 45],
            [['text'], 'string', 'max' => 20000],
        ];
    }

    public static function updateRules($text){
        $rules = Other::findOne(['category' => 'rules']);
        $rules->text = $text;
        $rules->date = date('Y-m-d');
        return $rules->update() !== false;
    }
}