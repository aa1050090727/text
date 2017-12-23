<?php

/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/11
 * Time: 18:02
 */

class Wechat{
    /*
        做验证操作
    */
    public function valid(){

        /*
            为了监控一下微信是不是有进来和我做校验，我用文件函数，做一下写入文件操作

            如果微信真的有发请求过来，一定会触发我的写入文件代码，生成一个文件出来
        */

        /*
            获取微信发送过来的 echostr，随机字符串
        */
        $echoStr = $_GET['echostr'];

        if($this->checkSignature()){
            ob_clean();//此函数用来丢弃输出缓冲区中的内容。 没有返回值。
            file_put_contents("wechat.txt","验证成功");
            echo $echoStr;
        }else{
            file_put_contents("wechat.txt","验证失败");
        }
    }

    /*
        验证算法
    */
    public function checkSignature(){

        if(!defined("TOKEN")){
            throw new Exception("TOKEN is not defined!");
        }
        /*
            获取微信发送过来的校验结果，也就是微信官方已经做好的饭团
        */
        $signature = $_GET['signature'];
        /*
            获取微信发送过来的材料之一，时间戳
        */
        $timestamp = $_GET['timestamp'];
        /*
            获取微信发送过来的材料之一，随机数
        */
        $nonce = $_GET['nonce'];
        /*
            双方都定义好的暗号
        */
        $token = TOKEN; //Token常量值

        //字典排序
        $tmpArr = array($token,$timestamp,$nonce);

        sort($tmpArr,SORT_STRING);
        /*
            将新排序后的数组再分割成字符串
        */
        $tmpStr = implode($tmpArr);
        /*
            sha1 加密
        */
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            return true;
        }
        else{
            return false;
        }
    }

}