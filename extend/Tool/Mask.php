<?php


namespace Tool;


class Mask
{
    /*
     *  用户名用*号处理
     *  用户名：英文、中文、中英文混合的、中英文字符混合
     *  首字母和末尾保留，中间用*号代替
     * */
    public static function mask_name_str($str = '')
    {
        if (empty($str) ){
            return $str;
        }

        $str = mb_convert_encoding( $str , 'UTF-8', 'auto' );
        //判断是否包含中文字符
        if(preg_match("/[\x{4e00}-\x{9fa5}]+/u", $str)) {
            //按照中文字符计算长度
            $len = mb_strlen($str, 'UTF-8');
            //echo '中文';
            if($len >= 3){
                //三个字符或三个字符以上掐头取尾，中间用*代替
                $str = mb_substr($str, 0, 1, 'UTF-8') . '*' . mb_substr($str, -1, 1, 'UTF-8');
            } elseif($len == 2) {
                //两个字符
                $str = mb_substr($str, 0, 1, 'UTF-8') . '*';
            }
        } elseif(preg_match("/[A-Za-z]/", $str)) {
            //按照英文字串计算长度
            $len = mb_strlen($str);
            //echo 'English';
            if($len >= 3) {
                //三个字符或三个字符以上掐头取尾，中间用*代替
                $str = mb_substr($str, 0, 1) . '*' . mb_substr($str, -1);
            } elseif($len == 2) {
                //两个字符
                $str = mb_substr($str, 0, 1) . '*';
            }
        }
        return $str;
    }
    /*
     * 手机号、固话加密
     * 示例：
     *  固话：0510-89754815 0510-8****815
     *  手机号：18221234158 18*******58
     * */
    public static function mask_phone_str($phone)
    {
        if (empty($phone)){
            return $phone;
        }

        $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$phone); //固定电话
        if($IsWhat == 1){
            return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$phone);
        }else{
            return  preg_replace('/(1[0-9]{1})[0-9]{7}([0-9]{2})/i','$1*******$2',$phone);
        }
    }
    /*
     * 地址中夹带数字加密
     * 示例：
     *  北京市124Ff：北京市***Ff
     * */
    public static function mask_address_str($address)
    {
        if (empty($address)){
            return $address;
        }

        $address = mb_convert_encoding( $address , 'UTF-8', 'auto' );
        $pattern = '/[0-9]/';
        if(preg_match_all($pattern, $address, $match)){
            return str_replace($match[0],'*',$address);
        }else{
            return $address;
        }
    }
}