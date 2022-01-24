<?php


namespace Tool;

/**
 * Class CryptAes 加解密基础类实现
 * @package Tool
 * @date 2021/11/24 0024 13:42
 * @auther wu
 */
class CryptAes
{
    //已无作用
    private static $aes_key = 'xbRp*4I#Q13sU@31'; //加密key
    private static $aes_iv  = 'XtIPuul7UaqUD*uj';  //加密iv
    private static $method  = "AES-128-CBC";  //加密方式

    //加密数据 (只能加密字符)
    public static function encrypt($data="",$aes_key = 'xbRp*4I#Q13sU@31',$aes_iv  = 'XtIPuul7UaqUD*uj',$method  = "AES-128-CBC") {
        $data = trim($data);
        $data = @openssl_encrypt($data,$method,$aes_key, true, $aes_iv);
        $data = base64_encode($data);
        return $data;
    }

    //解密数据 (只能解密字符串)
    public static function decrypt($data="",$aes_key = 'xbRp*4I#Q13sU@31',$aes_iv  = 'XtIPuul7UaqUD*uj',$method  = "AES-128-CBC") {
        $data = trim($data);
        if(empty($data)){
            return "";
        }
        $data = base64_decode($data);
        $data = @openssl_decrypt($data, $method,$aes_key, true, $aes_iv);
        return $data;
    }
    public static function sign($data,$key){
        $dst = hash_hmac('sha256', $data, $key, true);
        return base64_encode($dst);
    }

    public static function secureRandom($length)
    {
        return @openssl_random_pseudo_bytes($length);
    }
}