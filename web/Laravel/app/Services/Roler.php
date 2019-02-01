<?php

namespace App\Services;

use App\ChallengeUser;
use App\CityUser;
use App\PrefectureUser;
use App\SchoolUser;
use Illuminate\Support\Facades\Auth;

class Roler
{
    const KEY_PREFECTURE = 'prefecture';
    const KEY_CITY       = 'city';
    const KEY_SCHOOL     = 'school';
    const KEY_CHALLENGE  = 'challenge';
    const KEY_GENERAL    = 'general';

    private $exUser;

    public function __construct(){
        $this->exUser = null;
    }

    public function getRoleKey(){
        $r = $this->getRole();
        $result = self::KEY_GENERAL;
        if($r instanceof PrefectureUser){
            $result = self::KEY_PREFECTURE;
        }elseif($r instanceof CityUser){
            $result = self::KEY_CITY;
        }elseif($r instanceof SchoolUser){
            $result = self::KEY_SCHOOL;
        }elseif($r instanceof ChallengeUser){
            $result = self::KEY_CHALLENGE;
        }
        return $result;
    }

    public function getRoleName(){
        $d = [
            self::KEY_PREFECTURE => '神奈川県',
            self::KEY_CITY       => '市町村',
            self::KEY_SCHOOL     => '学校',
            self::KEY_CHALLENGE  => 'チャレンジ',
            self::KEY_GENERAL    => 'その他',
        ];

        return $d[$this->getRoleKey()] . 'ユーザ';
    }

    public function isPrefectureUser(){
        return $this->getRoleKey() === self::KEY_PREFECTURE;
    }

    public function isCityUser(){
        return $this->getRoleKey() === self::KEY_CITY;
    }

    public function isSchoolUser(){
        return $this->getRoleKey() === self::KEY_SCHOOL;
    }

    public function isChallengeUser(){
        return $this->getRoleKey() === self::KEY_CHALLENGE;
    }

    public function getRole(){
        if(is_null($this->exUser)){
            $user = Auth::user();
            $this->exUser = PrefectureUser::where('user_id', '=', $user->id)->first();
            if(is_null($this->exUser)){
                $this->exUser = CityUser::where('user_id', '=', $user->id)->first();
                if(is_null($this->exUser)){
                    $this->exUser = SchoolUser::where('user_id', '=', $user->id)->first();
                    if(is_null($this->exUser)){
                        $this->exUser = ChallengeUser::where('user_id', '=', $user->id)->first();
                        if(is_null($this->exUser)){
                            return null;
                        }else{
                            return $this->exUser;
                        }
                    }else{
                        return $this->exUser;
                    }
                }else{
                    return $this->exUser;
                }
            }else{
                return $this->exUser;
            }
        }else{
            return $this->exUser;
        }
    }
}
