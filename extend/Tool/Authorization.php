<?php


namespace Tool;

use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Trigger;
use Exception;

/**
 * Class Authorization 用于签发和验证授权token
 * @package Tool
 * @date 2021/11/24 0024 14:37
 * @auther wu
 */
class Authorization
{
    private static $app_key;       //签发授权token的秘钥
    private static $auth_interface;//无需验证token的api接口

    public function __construct ()
    {
        $auth_token = Config::getInstance()->getConf( 'AUTH_TOKEN' );

        self::$app_key = $auth_token[ 'key' ];

        self::$auth_interface = Config::getInstance()->getConf( 'AUTH_INTERFACE' );
    }

    /**
     * @method:签发授权token
     * @param string $shop_id 授权对象 店铺id
     * @return string
     * @throws Trigger
     * @date 2021/11/24 0024 16:11
     * @auther wu
     */
    public static function issueAuth ( $shop_id )
    {
        if ( empty( $shop_id ) ) {
            Trigger::getInstance()->error( '缺少店铺id' );
        }

        //计算有效期
        $expiry_time = strtotime("+3 month") - time();

        // 过期时间: 当前Unix秒数+有效期
        $ctime = time() + $expiry_time;

        // 生成签名授权码: 将三个参数以&符号拼接，进行md5加密并取加密值的末8位，再与$ctime拼接
        $sign_str = substr( md5( $shop_id . '&' . self::$app_key . '&' . $ctime ), -8 ) . $ctime;

        return CryptAes::encrypt( $sign_str, self::$app_key );
    }

    /**
     * @method:验证授权
     * @param string $auth_token 授权token
     * @param string $shop_id 授权对象 店铺id
     * @param string $request_method 请求地址 用于过滤特殊api
     * @return bool
     * @date 2021/11/24 0024 15:50
     * @auther wu
     * @throws Exception
     * @throws Trigger
     */
    public static function VerifyAuth ( $auth_token, $shop_id, $request_method )
    {
        if ( empty( $request_method ) ) {
            throw new Exception( '请求地址错误' );
        }
        if ( !empty( self::$auth_interface ) ) {
            if ( in_array( $request_method, self::$auth_interface ) ) {
                return true;
            }
        }

        //解码
        $auth_token = CryptAes::decrypt( $auth_token, self::$app_key );

        // 验证授权码完整性（18个字符）
        if ( strlen( $auth_token ) != 18 ) {
            throw new Exception( '签名授权码不完整,验证失败' );
        }

        // 拆分字符串 $sign_str
        $sign_str            = substr( $auth_token, 0, 8 );  // 签名授权
        $expiration_time_str = substr( $auth_token, 8, 10 ); // 过期时间

        // 构造签名验证字符串
        $sign = substr( md5( $shop_id . '&' . self::$app_key . '&' . $expiration_time_str ), -8 );

        //计算有效期
        $expiry_time = strtotime("+3 month") - time();

        $result = ( ( $expiration_time_str - time() ) > 0 && ( $expiration_time_str - time() ) < $expiry_time && ( $sign == $sign_str ) ) ? true : false;

        if ( $result == false ) {
            throw new Exception( '授权过期,请重新授权' );
        }

        return true;
    }

}