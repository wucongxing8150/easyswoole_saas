<?php

use EasySwoole\Log\LoggerInterface;
use Tool\CryptAes;

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT'           => 9510,
        'SERVER_TYPE'    => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER
        'SOCK_TYPE'      => SWOOLE_TCP,
        'RUN_MODEL'      => SWOOLE_PROCESS,
        'SETTING'        => [
            'worker_num'    => 8,
            'reload_async'  => true,
            'max_wait_time' => 3
        ],
        'TASK'           => [
            'workerNum'     => 4,
            'maxRunningNum' => 128,
            'timeout'       => 15
        ]
    ],
    "LOG"         => [
        'dir'            => null,
        'level'          => LoggerInterface::LOG_LEVEL_DEBUG,
        'handler'        => null,
        'logConsole'     => true,
        'displayConsole' => true,
        'ignoreCategory' => []
    ],
    'TEMP_DIR'       => "/tmp",
    'MYSQL'     => [
        'host'              => '127.0.0.1', // 数据库地址
        'port'              => 3306, // 数据库端口
        'user'              => 'root', // 数据库用户名
        'password'          => CryptAes::decrypt( "rNZskoGNRDrG2G4HiN0LrA==" ), // 数据库用户密码
        'timeout'           => 45, // 数据库连接超时时间
        'charset'           => 'utf8', // 数据库字符编码
        'database'          => 'saas', // 数据库名
        'autoPing'          => 2, // 自动 ping 客户端链接的间隔
        'strict_type'       => false, // 不开启严格模式
        'fetch_mode'        => false,
        'returnCollection'  => true, // 设置返回结果为 数组
        // 配置 数据库 连接池配置，配置详细说明请看连接池组件 https://www.easyswoole.com/Components/Pool/introduction.html
        'intervalCheckTime' => 15 * 1000, // 设置 连接池定时器执行频率
        'maxIdleTime'       => 10, // 设置 连接池对象最大闲置时间 (秒)
        'maxObjectNum'      => 5, // 设置 连接池最大数量
        'minObjectNum'      => 3, // 设置 连接池最小数量
        'getObjectTimeout'  => 3.0, // 设置 获取连接池的超时时间
        'loadAverageTime'   => 0.001, // 设置 负载阈值
    ],
];
