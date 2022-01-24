<?php

//异常类返回code码
const EXCEPTION_CODE = -1;

/**
 * @method:打印使用，格式化打印数据
 * @param mixed ...$vars
 * @date 2021/11/5 0005 15:10
 * @auther wu
 */
function dump ( ...$vars )
{
    ob_start();
    var_dump( ...$vars );

    $output = ob_get_clean();
    $output = preg_replace( '/\]\=\>\n(\s+)/m', '] => ', $output );

    if ( PHP_SAPI == 'cli' ) {
        $output = PHP_EOL . $output . PHP_EOL;
    } else {
        if ( !extension_loaded( 'xdebug' ) ) {
            $output = htmlspecialchars( $output, ENT_SUBSTITUTE );
        }
        $output = '<pre>' . $output . '</pre>';
    }

    echo $output;
}

/**
 * @method:正则验证数据
 * @param string $regex 正则表达式
 * @param string $content 需验证内容
 * @return bool
 * @date 2021/12/2 0002 17:47
 * @auther wu
 */
function pregMatch($regex,$content)
{
    $match = "";
    preg_match($regex,$content,$match);
    if(empty($match))
    {
        return false;
    }
    return true;
}

/**
 * @method:curl请求
 * @param string $url 请求地址
 * @param bool $params 请求参数
 * @param int $ispost 是否post请求
 * @param int $is_json 是否参数转换为json
 * @return mixed
 * @date 2021/6/25 0025 10:24
 * @auther wu
 */
function getContentByCurl($url, $params = false, $ispost = 0,$is_json = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    if ($ispost) {
        if($is_json){
            $http_header = array(
                'Content-type: application/json'
            );
        }else{
            $http_header = array(
                'Content-Type:application/x-www-form-urlencoded;charset=utf-8'
            );
        }
        curl_setopt ($ch, CURLOPT_HEADER, false );
        curl_setopt ($ch, CURLOPT_HTTPHEADER,$http_header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        curl_setopt($ch, CURLOPT_URL, $url);
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response,true);
}