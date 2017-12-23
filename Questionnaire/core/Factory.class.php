<?php

/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/11/7
 * Time: 23:11
 */
//自动引用--工厂类
class Factory
{
    private static $obj;
    public static function createFactory(){
        if(self::$obj == null){
            self::$obj = new Factory();
        }
        return self::$obj;
    }
    private function __construct()
    {
        require_once "./core/Control.class.php";
        require_once "./core/Model.class.php";

        require_once "./public/wechat/Wechat.class.php"; //引用微信类
//        require_once "./public/Page.class.php";  //引用分页类
        //require_once "./public/myLog/myLog.class.php";
        //这里还可以引用一些插件类
        spl_autoload_register([__CLASS__,"control"]);
        spl_autoload_register([__CLASS__,"model"]);
    }
    //自动引用控制器基类
    public function control($classname){
        $file = "./control/".$classname.".class.php";
        if(file_exists($file)){
            require_once $file;
        }
    }
    //自动引用模型基类
    public function model($classname){
        $file = "./model/".$classname.".class.php";
        if(file_exists($file)){
            require_once $file;
        }
    }
    public function run(){
        $type = isset($_GET["type"])?$_GET["type"]:"Main";
        $method = isset($_GET["method"])?$_GET["method"]:"showMain";
        $control = $type."Con";
        if(class_exists($control)){  //检查类是否定义  bool class_exists ( string $class_name [, bool $autoload = true ] )
            $con = new $control();
            $con->doaction($method);
        }else{
            include "./view/notFound.html";
        }

    }
}