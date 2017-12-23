<?php

/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/11/7
 * Time: 22:07
 */
//控制器基类
abstract class Control
{
//    abstract public function doaction($method);
    //自动引用方法
    final public function doaction($method){
        if(method_exists($this,$method)){ //检查类的方法是否存在 bool method_exists ( mixed $object , string $method_name )
            $this->$method();
        }else{
            $this->showView('./view/notFound.html');
        }
    }

    public function showView($url,$data=null){
        include_once $url;
    }
}