<?php

namespace app\models;


class TruckersMP{

    public static function getUserID($steamid){
        $json = self::requestPlayer($steamid);
        return !$json->error ? $json->response->id : false;
    }

    public static function isMemberBanned($truckersmp){
        $banned = false;

        $id = explode('/', $truckersmp)[4]; // convert url to numeric id

        $bans = self::requestBans($id);
        foreach ($bans->response as $ban){
            $expiration = new \DateTime($ban->expiration);
            $now = new \DateTime();
            if($expiration > $now && $ban->active === true){
                $banned = true;
                break;
            }
        }

        return $banned;
    }

    public static function getMemberTruckersMpNickname($steamid){
        $json = self::requestPlayer($steamid);
        if(!$json->error){
            return $json->response->name;
        }else{
            return false;
        }
    }

    public static function getServersList($game = null){
        $servers = self::getServersInfo();
        $servers_list = array();
        if($game != null){
            foreach($servers->response as $server){
                if($game == 'ets' && $server->game == 'ETS2' || $game == 'ats' && $server->game == 'ATS'){
                    $servers_list[$server->shortname . '_' . $server->game] = $server->name;
                }
            }
        }else{
            foreach($servers->response as $server){
                $servers_list[$server->game][$server->shortname . '_' . $server->game] = $server->name;
            }
        }
        return $servers_list;
    }

    public static function getServerName($short){
        $servers = self::getServersInfo();
        $name = null;
        foreach($servers->response as $server){
            if($server->shortname.'_'.$server->game == $short){
                $name = $server->name;
            }
        }
        return $name;
    }

    private static function requestPlayer($id){
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,'https://api.truckersmp.com/v2/player/'.$id);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return json_decode($data);
//        return json_decode(file_get_contents('https://api.truckersmp.com/v2/player/'.$id));
    }

    private static function requestBans($id){
        return json_decode(file_get_contents('https://api.truckersmp.com/v2/bans/'.$id));
    }

    private static function getServersInfo(){
        return json_decode(file_get_contents('https://api.truckersmp.com/v2/servers'));
    }

}