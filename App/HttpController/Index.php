<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\Controller;
use App\Model\Saas\Enterprise as EnterpriseModel;
use App\Model\Saas\DbConfig as DbConfigModel;
use App\Model\Saas\SaasErpEnterprise as SaasErpEnterpriseModel;
use App\Model\Oms\Enterprise as OmsEnterpriseModel;
use EasySwoole\Utility\SnowFlake;
use Tool\CryptAes;
use Tool\InitDb;
use App\Model\Oms\User as OmsUserModel;
use App\Model\Saas\OmsUser as SaasOmsUserModel;

class Index extends Controller
{

    public function index ()
    {
        $db_config = array();
        $db_config['db_address'] = "127.0.0.1";
        $db_config['db_port'] = "3306";
        $db_config['db_account_number'] = "root";
        $db_config['db_pwd'] = CryptAes::decrypt( "rNZskoGNRDrG2G4HiN0LrA==" );
        $db_config['db_name'] = "Test";

        $OmsUserModel           = new OmsUserModel();

        //重置数据库链接
        InitDb::switchDb( $db_config );

        $OmsUserModel->connectionName = $db_config[ 'db_name' ];

        $params                    = array();
        $params[ 'user_id' ] = "1";
        $oms_user                  = $OmsUserModel->getOne( $params );
        var_dump($oms_user);

        $this->response()->write( '成功' );
    }

    function test ()
    {
        $this->response()->write( 'this is test' );
    }

    protected function actionNotFound ( ?string $action )
    {
        $this->response()->withStatus( 404 );
        $file = EASYSWOOLE_ROOT . '/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if ( !is_file( $file ) ) {
            $file = EASYSWOOLE_ROOT . '/src/Resource/Http/404.html';
        }
        $this->response()->write( file_get_contents( $file ) );
    }
}