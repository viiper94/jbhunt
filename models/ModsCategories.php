<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ModsCategories extends ActiveRecord{

    public function rules(){
        return [
            [['title', 'picture'], 'required'],
            [['for_ets'], 'integer'],
            [['title', 'picture', 'name'], 'string', 'max' => 45],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => 'Title',
            'for_ets' => 'For Ets',
            'picture' => 'Picture',
        ];
    }

    public static function getCatsWithSubCats(){
        $all_categories = ModsCategories::find()->all();
        $categories = array();
        foreach($all_categories as $category){
            $subcategories = ModsSubcategories::findAll(['category_id' => $category->id]);
            $cat_title = $category->title;
            $cat_title .= $category->game == 'ets' ? ' - ETS2' : ' - ATS';
            foreach($subcategories as $subcategory){
                $categories[$cat_title][$category->game.'/'.$category->name.'/'.$subcategory->name] = $subcategory->title;
            }
        }
        return $categories;
    }

}