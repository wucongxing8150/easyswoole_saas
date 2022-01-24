<?php


namespace App\Exception;

use EasySwoole\EasySwoole\Logger;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use Throwable;

/**
 * Class ExceptionHandler 自定义异常类
 * @package App\Exception
 * @date 2021/11/24 0024 19:58
 * @auther wu
 */
class ExceptionHandler
{
    /**
     * @method:处理异常 返回值客户端
     * @param Throwable $exception
     * @param Request $request
     * @param Response $response
     * @return bool
     * @date 2021/11/24 0024 19:59
     * @auther wu
     */
    public static function handle ( Throwable $exception, Request $request, Response $response )
    {
        $code = $exception->getCode();

        $data[ 'code' ] = ($code == -1) ? $code : EXCEPTION_CODE;
        $data[ 'msg' ]  = empty( $exception->getMessage() ) ? "未知错误" : $exception->getMessage();
        $data[ 'data' ] = array();
        $result         = json_encode( $data, JSON_UNESCAPED_UNICODE );

        Logger::getInstance()->info( "Abnormal:" . $result);

        return $response->withHeader( "Content-Type", "application/json;charset=UTF-8" )
            ->withHeader( "Access-Control-Allow-Origin", "*" )
            ->write( $result );
    }
}