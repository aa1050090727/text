<?php

/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/5
 * Time: 23:32
 */
class MainCon extends Control
{
    //显示主页面
    public function showMain(){
        $this->showView("./view/main.html");
    }
}