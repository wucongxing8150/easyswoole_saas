<?php


namespace Tool;


use EasySwoole\EasySwoole\Config;
use Exception;

/**
 * Class Encrypt 加密功能实现
 * @package Tool
 * @date 2021/11/24 0024 20:02
 * @auther wu
 */
class Encrypt
{
    private static $aes_iv;             //加密iv 位数
    private static $app_secret_key_map; //加密秘钥和私钥 key=>value结构
    private static $method;             //加密方式

    public function __construct ()
    {
        $config = Config::getInstance()->getConf( 'AES' );

        self::$app_secret_key_map = $config[ 'appSecretKeyMap' ];
        self::$aes_iv             = $config[ 'cipherIvSize' ];
        self::$method             = "AES-128-CBC";
    }

    //加密返回数据
    public static function encrypt_response ( $data, $app_key, $nonce )
    {
        if ( !isset( self::$app_secret_key_map[ $app_key ] ) ) {
            throw new Exception( '秘钥错误' );
        }

        $secretKey = self::$app_secret_key_map[ $app_key ];


        $iv = CryptAes::secureRandom( self::$aes_iv );
        $iv = str_pad( $iv, self::$aes_iv, "\x00" );

        $jsonParams = json_encode( $data, JSON_UNESCAPED_UNICODE );

        $aesData    = CryptAes::encrypt( $jsonParams, $secretKey, $iv, "AES-128-CBC" );
        $aesData    = $iv . $aesData;
        $aesData    = base64_encode( $aesData );

        $result                  = array();
        $result[ 'encryptData' ] = $aesData;
        $result[ 'reqNonce' ]    = $nonce;

        return $result;
    }
}