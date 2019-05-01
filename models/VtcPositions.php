<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class VtcPositions extends ActiveRecord{

    public function rules(){
        return [
            [['admin'], 'integer'],
            [['name'], 'string', 'max' => 45],
        ];
    }

}
