<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/11/12
 * Time: 17:31
 */

require_once "./core/Factory.class.php";
//require_once "./control/LoginCon.class.php";
$factory = Factory::createFactory();
$factory->run();