<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Variations extends ActiveRecord{

    public static function tableName()    {
        return 'variations';
    }

    public function rules(){
        return [
            [['name', 'game'], 'required'],
            [['name', 'image', 'description'], 'string'],
            [['game'], 'string', 'max' => 4],
            [['image'], 'safe'],
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'name' => 'Название',
            'image' => 'Изображение',
            'description' => 'Описание',
            'game' => 'Игра',
        ];
    }

    public function editVariation(){
        $this->attributes = Yii::$app->request->post();
        if($image = UploadedFile::getInstanceByName('image')){
            $this->image = $image->getBaseName().'.'.$image->extension;
            $image->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/variations/'.$this->image);
        }
        return $this->save() !== false;
    }

}