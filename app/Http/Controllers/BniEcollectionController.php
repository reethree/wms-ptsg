<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BniEcollectionController extends Controller
{
    protected $client_id;
    protected $secret_key;
    protected $url;
    protected $TIME_DIFF_LIMIT;

    public function __construct() {
        
        $this->client_id = '585';
        $this->secret_key = 'e68cc6c6a3f5fa59be436534757439e';
        $this->TIME_DIFF_LIMIT = 480;
    }
    
    public static function encrypt(array $json_data) {
            return self::doubleEncrypt(strrev(time()) . '.' . json_encode($json_data), $this->client_id, $this->secret_key);
    }

    public static function decrypt($hased_string) {
            $parsed_string = self::doubleDecrypt($hased_string, $this->client_id, $this->secret_key);
            list($timestamp, $data) = array_pad(explode('.', $parsed_string, 2), 2, null);
            if (self::tsDiff(strrev($timestamp)) === true) {
                    return json_decode($data, true);
            }
            return null;
    }

    private static function tsDiff($ts) {
            return abs($ts - time()) <= $this->TIME_DIFF_LIMIT;
    }

    private static function doubleEncrypt($string, $cid, $secret) {
            $result = '';
            $result = self::enc($string, $cid);
            $result = self::enc($result, $secret);
            return strtr(rtrim(base64_encode($result), '='), '+/', '-_');
    }

    private static function enc($string, $key) {
            $result = '';
            $strls = strlen($string);
            $strlk = strlen($key);
            for($i = 0; $i < $strls; $i++) {
                    $char = substr($string, $i, 1);
                    $keychar = substr($key, ($i % $strlk) - 1, 1);
                    $char = chr((ord($char) + ord($keychar)) % 128);
                    $result .= $char;
            }
            return $result;
    }

    private static function doubleDecrypt($string, $cid, $secret) {
            $result = base64_decode(strtr(str_pad($string, ceil(strlen($string) / 4) * 4, '=', STR_PAD_RIGHT), '-_', '+/'));
            $result = self::dec($result, $cid);
            $result = self::dec($result, $secret);
            return $result;
    }

    private static function dec($string, $key) {
            $result = '';
            $strls = strlen($string);
            $strlk = strlen($key);
            for($i = 0; $i < $strls; $i++) {
                    $char = substr($string, $i, 1);
                    $keychar = substr($key, ($i % $strlk) - 1, 1);
                    $char = chr(((ord($char) - ord($keychar)) + 256) % 128);
                    $result .= $char;
            }
            return $result;
    }

}


