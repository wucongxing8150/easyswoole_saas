<?php

namespace Tool;

use EasySwoole\EasySwoole\Config;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use Exception;
use Throwable;

class InitDb
{
    /**
     * @method:切换数据库
     * @param array $db_config
     * @throws \EasySwoole\Pool\Exception\Exception
     * @date   2022/1/21 15:57
     * @auther wu
     */
    public static function switchDb ($db_config = array())
    {

        if ( empty( $db_config ) ) {
            throw new Exception( '切换数据库失败' );
        }

        $config = new \EasySwoole\ORM\Db\Config();
        $config->setHost( $db_config[ 'db_address' ] );
        $config->setPort( $db_config[ 'db_port' ] );
        $config->setUser( $db_config[ 'db_account_number' ] );
        $config->setPassword( CryptAes::decrypt( $db_config[ 'db_pwd' ] ) );
        $config->setTimeout( 15 );                  // 超时时间
        $config->setDatabase( $db_config[ 'db_name' ] );

        //连接池配置
        $config->setGetObjectTimeout( 3.0 );        //设置获取连接池对象超时时间
        $config->setIntervalCheckTime( 15 * 1000 ); //设置检测连接存活执行回收和创建的周期
        $config->setMaxIdleTime( 10 );              //连接池对象最大闲置时间(秒)
        $config->setMinObjectNum( 1 );              //设置最小连接池存在连接对象数量
        $config->setMaxObjectNum( 2 );              //设置最大连接池存在连接对象数量
        $config->setAutoPing( 2 );                  //设置自动ping客户端链接的间隔
        $res = DbManager::getInstance()->addConnection( new Connection( $config ), $db_config[ 'db_name' ] );
        if ( empty( $res ) ) {
            throw new Exception( '切换数据库失败' );
        }
    }
}