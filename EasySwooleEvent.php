<?php


namespace EasySwoole\EasySwoole;


use App\Exception\ExceptionHandler;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Config as Config;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\ORM\Db\Config as DbConfig;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');

        //注册自定义异常处理类
        Di::getInstance()->set( SysConst::HTTP_EXCEPTION_HANDLER, [ ExceptionHandler::class, 'handle' ] );

        //设置指定连接名称 后期可通过连接名称操作不同的数据库
        $dbConfig = new DbConfig( Config::getInstance()->getConf( "MYSQL" ) );
        DbManager::getInstance()->addConnection( new Connection( $dbConfig ) );

    }

    public static function mainServerCreate(EventRegister $register)
    {

    }
}