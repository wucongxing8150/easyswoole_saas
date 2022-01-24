<?php

namespace App\Model\Oms;

use EasySwoole\ORM\AbstractModel;

class User extends AbstractModel
{
    #数据表名称
    protected $tableName = 'bs_user';

    protected $primaryKey = 'user_id';

    public $connectionName = '';

    /**
     * @method:多条查询
     * @param array $where 查询条件
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * @date 2021/11/30 0030 13:58
     * @auther wu
     */
    public function search($where = array())
    {
        return $this->where($where)->all();
    }

    /**
     * @method:单条查询
     * @param array $where 查询条件
     * @return User|array|bool|AbstractModel|\EasySwoole\ORM\Db\Cursor|\EasySwoole\ORM\Db\CursorInterface|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * @date 2021/11/30 0030 13:53
     * @auther wu
     */
    public function getOne($where = array())
    {
        return self::where($where)->get();
    }

    /**
     * @method:分页查询
     * @param array $where 查询条件
     * @param string|int $page 页码
     * @param string|int $pageSize 每页个数
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * @date 2021/11/30 0030 10:40
     * @auther wu
     */
    public function pageSearch($where, $page, $pageSize)
    {
        $list = self::where($where)->limit($pageSize * ($page - 1), $pageSize)->withTotalCount()->all()->toArray(null, false);
        $total = $this->lastQueryResult()->getTotalCount();
        return ['total' => $total, 'list' => $list];
    }

    /**
     * @method:新增
     * @param array $data 新增数据
     * @return bool|int
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * @date 2021/11/30 0030 13:59
     * @auther wu
     */
    public function add($data = array())
    {
        return self::data($data)->save();
    }

    /**
     * @method:修改
     * @param array $where 查询条件
     * @param array $data 修改数据
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     * @date 2021/11/30 0030 14:00
     * @auther wu
     */
    public function modify($where = array(),$data = array())
    {
        return self::data($data)->where($where)->update();
    }
}