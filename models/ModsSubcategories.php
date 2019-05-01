<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ModsSubcategories extends ActiveRecord{

	public $cat_id;
	public $cat_title;
	public $cat_name;
	public $cat_image;

    public function rules(){
        return [
            [['category_id', 'title'], 'required'],
            [['category_id', 'for_ets'], 'integer'],
            [['title', 'name'], 'string', 'max' => 45],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'title' => 'Title',
            'for_ets' => 'For Ets',
        ];
    }
}
