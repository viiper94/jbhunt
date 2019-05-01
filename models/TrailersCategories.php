<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class TrailersCategories extends ActiveRecord{

    public function rules(){
        return [
            [['title', 'name'], 'required'],
            [['title', 'name'], 'string', 'max' => 64],
        ];
    }

}
