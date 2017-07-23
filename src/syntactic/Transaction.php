<?php
/**
 * Created by PhpStorm.
 * User: 不二进制·Number
 * Time: 2017年07月24日03:14:22
 */

namespace phpcmx\mysql\syntactic;

use PDO;
use phpcmx\mysql\DBConfig;

class Transaction extends BaseDb
{


    /**
     * 生成sql并运行返回结果
     *
     * @return mixed
     */
    protected function sqlExecute()
    {
        //  Implement sqlExecute() method.
    }

    public function beginTransaction(){
        $pdoLink = DBConfig::getInstance()->getDbCache($this->_dbName);

        $pdoLink->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);//这个是通过设置属性方法进行关闭自动提交和上面的功能一样
        $pdoLink->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $pdoLink->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);//开启异常处理
        return $pdoLink->beginTransaction();
    }

    public function rollBack()
    {
        $pdoLink = DBConfig::getInstance()->getDbCache($this->_dbName);

        $re = $pdoLink->rollBack();
//        $this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
        return $re;
    }

    public function commit(){
        $pdoLink = DBConfig::getInstance()->getDbCache($this->_dbName);

        $re = $pdoLink->commit();
        $pdoLink->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
        return $re;
    }

    /**
     * 检查必须要操作的流程
     * @return void
     */
    protected function checkRequire()
    {
        // TODO: Implement checkRequire() method.
    }
}