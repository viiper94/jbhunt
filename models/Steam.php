<?php

namespace app\models;

use ErrorException;
use LightOpenID;

class Steam{

    private static $key = '4B20D16FDEF836EB866804F847F585A3';

    public static function getUser64ID($url){
        $url = str_replace(['http://', 'https://'], '', $url);
        $url = explode('/', $url);
        if (!preg_match('/^7656119[0-9]{10}$/i', $url[2])){
            $json = json_decode(file_get_contents('http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key='.self::$key.'&vanityurl='.$url[2]));
            return $json->response->success == '1' ? $json->response->steamid : false;
        }else{
            return $url[2];
        }
    }

    public static function getPlayerNickname($steamid){
        $json = json_decode(file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.self::$key.'&steamids='.$steamid));
        if(count($json->response->players) > 0){
            return $json->response->players[0]->personaname;
        }else{
            return false;
        }
    }

	public static function getUsersGames($steamid){
		$json = json_decode(self::getData('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key='.self::$key.'&steamid='.$steamid.'&format=json'));
		return property_exists($json->response, 'games') ? $json->response->games : false;
    }

	public static function getUserPlayTime($steamid, $appid){
		if($games = self::getUsersGames($steamid)){
			foreach($games as $game){
				if($game->appid == $appid){
					$playtime = round(intval($game->playtime_forever) / 60);
					break;
				}
			}
			return $playtime;
		}else{
			return false;
		}
    }

    public static function authUser(){
//		require '../lib/lightopenid/openid.php';
		try{
			$openid = new LightOpenID('http://'.$_SERVER['HTTP_HOST']);
			if(!$openid->mode){
				$openid->identity = 'http://steamcommunity.com/openid/?l=russian';
				header('Location: '. $openid->authUrl());
				exit();
			}elseif($openid->mode == 'cancel'){
				return false;
			}else{
				if($openid->validate() !== false){
					$id = $openid->data['openid_identity'];
					$ptn = '/^https:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/';
					preg_match($ptn, $id, $matches);
					$url = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.self::$key.'&steamids='.$matches[1];
					$json_object = self::getData($url);
					$json = json_decode($json_object);
					return $json->response->players[0];
				}else{
					return false;
				}
			}
		}catch(ErrorException $e){
			return false;
		}
	}

	public static function getData($url){
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

}