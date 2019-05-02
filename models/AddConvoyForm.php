<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AddConvoyForm extends Model{

    public $picture_full;
    public $picture_small;
    public $extra_picture;
    public $add_info;
    public $start_city;
    public $start_company;
    public $finish_city;
    public $finish_company;
    public $rest;
    public $description;
    public $server = 'eu2_ets';
    public $length;
    public $title;
    public $departure_time;
    public $meeting_time;
    public $date;
    public $trailer;
    public $tr_image;
    public $tr_name;
    public $truck_var;
    public $communications;
    public $author;
    public $game = 'ets';
    public $visible = true;
    public $open = false;
    public $dlc = array();
    public $map_remove = false;
    public $attach_var_photo = true;

    public $convoy;

    public function __construct($id = null){
        if(isset($id)){
            $convoy = Convoys::find()
				->select(['convoys.*', 'trailers.name AS tr_name', 'trailers.picture AS tr_image'])
				->leftJoin('trailers', 'trailers.id = convoys.trailer')
				->where(['convoys.id' => $id])
				->one();
            $this->convoy = $convoy;
            $this->start_city = $convoy->start_city;
            $this->picture_small = $convoy->picture_small;
            $this->start_company = $convoy->start_company;
            $this->finish_city = $convoy->finish_city;
            $this->finish_company = $convoy->finish_company;
            $this->rest = $convoy->rest;
            $this->description = $convoy->description;
            $this->server = $convoy->server;
            $this->length = $convoy->length;
            $d_time = new \DateTime($convoy->departure_time);
            $m_time = new \DateTime($convoy->meeting_time);
            $this->departure_time = $d_time->format('H:i');
            $this->meeting_time = $m_time->format('H:i');
            $this->date = $d_time->format('Y-m-d');
            $this->trailer = $convoy->trailer;
            $this->tr_image = $convoy->tr_image;
            $this->tr_name = $convoy->tr_name;
            $this->truck_var = explode(',', $convoy->truck_var)[0];
            $this->attach_var_photo = explode(',', $convoy->truck_var)[1] == '1';
            $this->title = $convoy->title;
            $this->communications = $convoy->communications;
            $this->visible = $convoy->visible;
            $this->open = $convoy->open;
            $this->extra_picture = $convoy->extra_picture;
            $this->add_info = $convoy->add_info;
            $this->author = $convoy->author;
            $this->game = $convoy->game;
            $this->dlc = unserialize($convoy->dlc);
        }else{
			$this->convoy = new Convoys();
			$this->game = Yii::$app->request->get('game', 'ets');
			$this->convoy->game = $this->game;
		}
    }

    public function rules() {
        return [
            [['start_city', 'start_company', 'finish_city', 'finish_company', 'server', 'departure_time', 'date'], 'required'],
            [['rest', 'description', 'length', 'title', 'communications', 'meeting_time'], 'string'],
            [['extra_picture', 'picture_full', 'picture_small'], 'file', 'extensions' => 'png, jpg', 'maxSize' => 16500000],
            [['open', 'visible', 'map_remove', 'attach_var_photo'], 'boolean'],
            [['add_info', 'author', 'game'], 'string'],
            [['dlc', 'trailer', 'truck_var'], 'safe']
        ];
    }

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'extra_picture' => 'Дополнительное изображение',
            'add_info' => 'Дополнительная информация',
            'start_city' => 'Стартовый город',
            'start_company' => 'Стартовое место',
            'finish_city' => 'Конечный город',
            'finish_company' => 'Конечное место',
            'rest' => 'Точка отдыха',
            'description' => 'Про конвой',
            'server' => 'Сервер',
            'length' => 'Протяженность маршрута',
            'departure_time' => 'Время выезда (по МСК)',
            'meeting_time' => 'Время сбора (по МСК)',
            'date' => 'Дата проведения конвоя',
            'trailer' => 'Трейлер',
            'truck_picture' => 'Изображение тягача',
            'truck_var' => 'Вариации тягача',
            'title' => 'Название конвоя',
            'communications' => 'Связь',
            'author' => 'Конвой сделал',
        ];
    }

    public function addConvoy(){
        $convoy = new Convoys();
        $convoy->start_city = $this->start_city;
        $convoy->start_company = $this->start_company;
        $convoy->finish_city = $this->finish_city;
        $convoy->finish_company = $this->finish_company;
        $convoy->rest = $this->rest;
        $convoy->description = $this->description;
        $convoy->server = $this->server;
        $convoy->length = $this->length;
        $date = new \DateTime($this->date);
        $convoy->departure_time = $date->format('Y-m-d ').$this->departure_time;
        if(!$this->meeting_time){
			$m_date = new \DateTime($convoy->departure_time);
			$m_date->sub(new \DateInterval('PT15M'));
			$convoy->meeting_time = $m_date->format('Y-m-d H:i');
		}else{
			$convoy->meeting_time = $date->format('Y-m-d ').$this->meeting_time;
		}
        $convoy->date = $this->date;
        $convoy->week_day = intval($date->format('N'));
        $convoy->trailer = $this->trailer;
        $convoy->truck_var = $this->truck_var.','.intval($this->attach_var_photo);
        $convoy->title = $this->title;
        $convoy->open = $this->open ? '1' : '0';
        $convoy->dlc = serialize($this->dlc);
        $convoy->visible = $this->visible ? '1' : '0';
        $convoy->communications = $this->communications;
        $convoy->add_info = $this->add_info;
        $convoy->author = $this->author;
        $convoy->game = Yii::$app->request->get('game');
        if($convoy->save() == 1){
            if($map_full = UploadedFile::getInstance($this, 'picture_full')){
                $convoy->picture_full = $convoy->id.'-f.'.$map_full->extension;
                $convoy->picture_small = $convoy->id.'-f.'.$map_full->extension;
                $map_full->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_full);
            }
            if($map_small = UploadedFile::getInstance($this, 'picture_small')){
                $convoy->picture_small = $convoy->id.'-s.'.$map_small->extension;
                $map_small->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_small);
            }
            if($extra_picture = UploadedFile::getInstance($this, 'extra_picture')){
				if($extra_picture->size > 1500000){
					$img = new Image();
					$img->load($extra_picture->tempName);
					if($img->getWidth() > 1920){
						$img->resizeToWidth(1920);
					}
					$img->save($extra_picture->tempName);
				}
                $convoy->extra_picture = time().'_'.str_replace(['.png', '.jpg'], '', $extra_picture->name).'_'.time().'.jpg';
				$extra_picture->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->extra_picture);
            }
            if(!User::isAdmin()) Mail::newMemberConvoyToAdmin($convoy->id);
            $convoy->update();
            return $convoy->id;
        }else{
            return false;
        }
    }

    public function editConvoy($id){
        $convoy = Convoys::findOne($id);
        $convoy->start_city = $this->start_city;
        $convoy->start_company = $this->start_company;
        $convoy->finish_city = $this->finish_city;
        $convoy->finish_company = $this->finish_company;
        $convoy->rest = $this->rest;
        $convoy->description = $this->description;
        $convoy->server = $this->server;
        $convoy->length = $this->length;
        $convoy->date = $this->date;
        $date = new \DateTime($this->date);
        if($convoy->departure_time != $date->format('Y-m-d ').$this->departure_time.':00'){
            $convoy->participants = null;
        }
        $convoy->departure_time = $date->format('Y-m-d ').$this->departure_time;
        $convoy->meeting_time = $date->format('Y-m-d ').$this->meeting_time;
        if(new \DateTime($convoy->departure_time) > new \DateTime()){
            $convoy->scores_set = '0';
        }
		$convoy->week_day = intval($date->format('N'));
        $convoy->trailer = $this->trailer;
        $convoy->truck_var = $this->truck_var.','.intval($this->attach_var_photo);
        $convoy->title = $this->title;
        $convoy->open = $this->open;
        $convoy->dlc = serialize($this->dlc);
        $convoy->visible = $this->visible;
        $convoy->communications = $this->communications;
        $convoy->add_info = $this->add_info;
        $convoy->author = $this->author;
        $convoy->updated = date('Y-m-d H:i');
        $convoy->updated_by = Yii::$app->user->id;
		if($map_full = UploadedFile::getInstance($this, 'picture_full')){
			$convoy->picture_full = $convoy->id.'-f.'.$map_full->extension;
			$convoy->picture_small = $convoy->id.'-f.'.$map_full->extension;
			$map_full->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_full);
		}
		if($map_small = UploadedFile::getInstance($this, 'picture_small')){
			$convoy->picture_small = $convoy->id.'-s.'.$map_small->extension;
			$map_small->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_small);
		}
		if($extra_picture = UploadedFile::getInstance($this, 'extra_picture')){
			if($extra_picture->size > 1500000){
				$img = new Image();
				$img->load($extra_picture->tempName);
				if($img->getWidth() > 1920){
					$img->resizeToWidth(1920);
				}
				$img->save($extra_picture->tempName);
			}
			$convoy->extra_picture = time().'_'.str_replace(['.png', '.jpg'], '', $extra_picture->name).'_'.time().'.jpg';
			$extra_picture->saveAs($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->extra_picture);
		}
        return $convoy->update() !== false;
    }

}