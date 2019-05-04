<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Convoys extends ActiveRecord{

	public $tr_image;
	public $tr_name;
	public $tr_mod_file_name;

    public static function tableName(){
        return 'convoys';
    }

    public function rules(){
        return [
            [['truck_var', 'time', 'date', 'updated'], 'safe'],
            [['visible', 'open', 'updated_by', 'week_day'], 'integer'],
            [['description'], 'string', 'max' => 2048],
            [['rest', 'participants'], 'string', 'max' => 1024],
            [['add_info'], 'string', 'max' => 8096],
            [['picture_full', 'picture_small', 'start_city', 'start_company', 'finish_city', 'finish_company', 'extra_picture'], 'string', 'max' => 255],
            [['server'], 'string', 'max' => 45],
            [['length'], 'string', 'max' => 10],
            [['game'], 'string', 'max' => 3],
            [['dlc', 'trailer', 'author'], 'string']
        ];
    }

    public static function getNearestConvoy(){
        $nearest_convoy_query = Convoys::find()
//            ->select(['id', 'title', 'picture_full', 'picture_small', 'description', 'departure_time'])
            ->where(['visible' => '1'])
            ->andWhere(['>=', 'departure_time', gmdate('Y-m-d ').(intval(gmdate('H'))+2).':'.gmdate('i:s')]);
        if(!User::isVtcMember()) $nearest_convoy_query = $nearest_convoy_query->andWhere(['open' => '1']); // only open convoys for guests
        $nearest_convoy = $nearest_convoy_query->orderBy(['date' => SORT_ASC])->one();
        return $nearest_convoy;
    }

    public static function getFutureConvoys(){
        $convoys_query = Convoys::find()->select(['id', 'picture_small', 'title', 'departure_time', 'visible'])
            ->andWhere(['>=', 'departure_time', gmdate('Y-m-d ').(intval(gmdate('H'))+2).':'.gmdate('i:s')]);
        if(!User::isVtcMember()) $convoys_query = $convoys_query->andWhere(['open' => '1']); // only open convoys for guests
        if(!User::isAdmin()) $convoys_query = $convoys_query->andWhere(['visible' => '1']); // only visible convoys for non-admins
        $convoys = $convoys_query->orderBy(['date' => SORT_ASC])->all();
        return $convoys;
    }

    public static function getPastConvoys(){
        if(User::isVtcMember() || User::isAdmin()){
            $hidden_convoys = Convoys::find()
                ->select(['id', 'picture_small', 'title', 'departure_time', 'visible', 'scores_set'])
                ->andWhere(['<', 'departure_time', gmdate('Y-m-d ') . (intval(gmdate('H')) + 2) . ':' . gmdate('i:s')]);
            if(!User::isAdmin()) $hidden_convoys->andWhere(['visible' => '1']); // only visible convoys for non-admins
            $hidden_convoys = $hidden_convoys->orderBy([
                'visible' => SORT_DESC,
                'week_day' => SORT_ASC,
                'departure_time' => SORT_ASC
            ])->all();

            return $hidden_convoys;
        }
        return false;
    }

    public static function deleteConvoy($id){
        $convoy = Convoys::findOne($id);
        if($convoy->picture_full && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_full)) {
            unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_full);
        }
        if($convoy->picture_small && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_small)) {
            unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_small);
        }
        if($convoy->extra_picture && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->extra_picture)) {
            unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->extra_picture);
        }
        return $convoy->delete();
    }

    public static function visibleConvoy($id, $action){
        $convoy = Convoys::findOne($id);
        $convoy->visible = $action == 'show' ? '1' : '0';
        return $convoy->update() == 1 ? true : false;
    }

    public static function deleteExtraPicture($id) {
        $convoy = Convoys::findOne($id);
		if(file_exists(Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->extra_picture)) {
			unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->extra_picture);
		}
        $convoy->extra_picture = null;
        $convoy->save();
    }

    public static function deleteMap($id) {
        $convoy = Convoys::findOne($id);
		if($convoy->picture_full && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_full)) {
			unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_full);
		}
		if($convoy->picture_small && file_exists($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_small)) {
			unlink($_SERVER['DOCUMENT_ROOT'].Yii::$app->request->baseUrl.'/images/convoys/'.$convoy->picture_small);
		}
        $convoy->picture_full = null;
        $convoy->picture_small = null;
        $convoy->save();
    }

    public function getVariationsByGame(){
        if($this->game == 'ets' || $this->game == ''){
            $vars = [
                '0' => 'Любая вариация',
                '1' => 'Вариация №1',
                '2' => 'Вариация №2.1 или 2.2',
                '21' => 'Вариация №2.1',
                '22' => 'Вариация №2.2',
                '3' => 'Вариация №3',
                '4' => 'Вариация №1 или №2',
                '5' => 'Вариация №1 или №3',
                '6' => 'Легковой автомобиль Scout',
                '7' => 'Тягач, как в описании',
            ];
        }else if($this->game == 'ats'){
            $vars = [
                '0' => 'Любой тягач',
                '6' => 'Тягач, как в описании',
                '7' => 'Легковой автомобиль Scout',
            ];
        }
        return $vars;
    }

    public function getVariationName($short, $link = false){
    	$vars = $this->getVariationsByGame();
        $variation = $vars[$short];
        if($link && ($short == '1' || $short == '21' || $short == '22' || $short == '3')){
            $variation = '<a href="'.Url::to(['site/variations', '#' => $short]).'">'.$variation.'</a>';
        }
        return $variation;
    }

    public static function getDLCString($dlc, $prefix = 'Для участия необходимо'){
        $need = false;
        $string = '<i class="material-icons left" style="font-size: 22px">warning</i>'.$prefix.' ';
        foreach ($dlc as $key => $item){
            if($item == '1'){
                $string .= 'DLC '.$key.', ';
                $need = true;
            }
        }
        return $need ? substr($string, 0, strlen($string) - 2) : false;
    }

	public static function getDLCList($game = null){
		$ets = [
			'Going East!' => 'Going East!',
			'Scandinavia' => 'Scandinavia',
			'Vive la France!' => 'Vive la France!',
			'Italia' => 'Italia',
			'Beyond the Baltic Sea' => 'Beyond the Baltic Sea',
			'High Power Cargo Pack' => 'High Power Cargo Pack',
			'Schwarzmüller Trailer Pack' => 'Schwarzmüller Trailer Pack',
			'Krone Trailer Pack' => 'Schwarzmüller Trailer Pack',
			'Special Transport' => 'Special Transport (ETS2)',
			'Heavy Cargo Pack (ETS2)' => 'Heavy Cargo Pack (ETS2)',
		];
		$ats = [
			'Heavy Cargo Pack (ATS)' => 'Heavy Cargo Pack (ATS)',
            'Special Transport' => 'Special Transport (ATS)',
			'New Mexico' => 'New Mexico',
			'Oregon' => 'Oregon',
//			'Washington' => 'Washington',
		];
		return $game ? $game : ArrayHelper::merge($ets, $ats);
    }

    public function getVarList(){
        $var_images = [
            '1' => 'var1',
            '21' => 'var2',
            '22' => 'var22'
        ];
        $list = '<ul class="var-list">';
        switch (explode(',', $this->truck_var)[0]){
            case '1' : $vars = ['1']; break;
            case '2' : $vars = ['21', '22']; break;
            case '21' : $vars = ['21']; break;
            case '22' : $vars = ['22']; break;
            case '3' : $vars = ['3']; break;
            case '4' : $vars = ['1', '21', '22']; break;
            case '5' : $vars = ['1', '3']; break;
            case '6' : $vars = ['6']; break;
            case '7' : $vars = ['7']; break;
            case '0' :
            default : $vars = ['0']; break;
        }
        foreach ($vars as $var){
            $list .= '<li><p class="var-name">'.$this->getVariationName($var, true).'</p>';
            if(explode(',', $this->truck_var)[1] == '1' && array_key_exists($var, $var_images)) {
                $list .= '<img class="responsive-img materialboxed z-depth-2" src="/assets/img/variations/'.$var_images[$var].'.jpg">';
            }
            $list .= '</li>';
        }
        return $list .= '</ul>';
    }

    public static function setConvoyScores($scores, $target, $lead = null){
        foreach($scores as $id => $score){
            $score = intval($score);
            if($score != 0){
                if($lead && $lead == $id && $score != 5){
                    $score += ($score/2);
                }
                VtcMembers::addScores($id, $score, $target);
            }
        }
        return true;
    }

    public static function changeConvoyParticipants($id, $user_id, $participate){
        $convoy = Convoys::findOne($id);
        $participants = unserialize($convoy->participants);
        $new_participants = [
            '100' => [],
            '50' => [],
            '0' => [],
        ];
        if($participants){
            foreach ($participants as $key => $participant) {
                foreach ($participant as $index => $val) {
                    if ($val != $user_id) {
                        $new_participants[$key][] = $val;
                    }
                }
            }
        }
        $new_participants[$participate][] = intval($user_id);
        $convoy->participants = serialize($new_participants);
        return $convoy->update() !== false ? $new_participants : $participants;
    }

    public static function getParticipantsData($array){
        $new_participants = [
            '100' => [],
            '50' => [],
            '0' => [],
        ];
        if($array){
            foreach ($array as $participate => $participants){
                foreach ($participants as $index => $participant) {
                    if($participant){
                        $new_participants[$participate][$index] = User::find()
                            ->select(['id', 'nickname', 'company', 'picture'])
                            ->where(['id' => $participant])
                            ->one();
                    }
                }
            }
        }
        return $new_participants;
    }

}