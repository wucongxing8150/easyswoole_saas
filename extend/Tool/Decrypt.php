<?php


namespace Tool;

use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Trigger;
use Exception;

/**
 * Class Decrypt 解密功能实现
 * @package Tool
 * @date 2021/11/24 0024 14:38
 * @auther wu
 */
class Decrypt
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

    /**
     * @method:解密请求参数
     * @param array $resqJsonRaw
     * @param string $appKey
     * @return array
     * @throws Trigger
     * @date 2021/11/24 0024 10:46
     * @auther wu
     */
    public static function decrypt_request ( $resqJsonRaw )
    {
        $app_key      = $resqJsonRaw[ 'appKey' ];
        $encrypt_data = $resqJsonRaw[ 'encryptData' ];
        $nonce        = $resqJsonRaw[ 'nonce' ];
        $timestamp    = $resqJsonRaw[ 'timestamp' ];
        $version      = $resqJsonRaw[ 'version' ];
        $sign         = $resqJsonRaw[ 'sign' ];

        if ( !isset( self::$app_secret_key_map[ $app_key ] ) ) {
            throw new Exception( '秘钥错误' );
        }

        $secretKey = self::$app_secret_key_map[ $app_key ];

        //组合签名
        $signSrc = $secretKey . "appKey" . $app_key . "encryptData" . $encrypt_data . "nonce" . $nonce . "timestamp" . $timestamp . "version" . $version . $secretKey;

        //验证签名
        $realSign = CryptAes::sign( $signSrc, $secretKey );

        if ( $realSign != $sign ) {
            throw new Exception( '签名不正确' );
        }

        $encrypt_data    = base64_decode( $encrypt_data );
        $iv              = substr( $encrypt_data, 0, self::$aes_iv );
        $realEncryptData = substr( $encrypt_data, self::$aes_iv, strlen( $encrypt_data ) - self::$aes_iv );

        $srcData = CryptAes::decrypt( $realEncryptData, $secretKey, $iv, "AES-128-CBC" );

        return json_decode( $srcData, true );
    }
}