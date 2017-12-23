<?php

/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/11/7
 * Time: 17:08
 */
//模型基类
class Model
{
    protected $dataBase;
    public function __construct()
    {
        require_once "./public/dataBase/DataBase.class.php";
        $config = include "./public/dataBase/config.php";
        $this->dataBase = DataBase::myDb($config);
    }
    public function sqlSelect($table,$condition = null){
        $sql = "select * from {$table} where 1=1";
        if(is_array($condition)){
            foreach($condition as $key=>$value){
                $sql.=" and $key='{$value}'";
            }
        }
        $res = $this->dataBase->dealSql($sql);
        return $res;
    }
}