<?php

/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/11
 * Time: 18:04
 */

define('TOKEN','chenlingfeng');
define('APPID','wx406faba3912d60d0');
define('APPSECRET','df64bb1774d68d45efa9ea23ebcd1e01');

class WechatCon extends Control
{
    public function __construct()
    {
        //require_once "./public/wechat/Wechat.class.php"; //引用微信类
        $this->wechat = new Wechat();
    }

    //微信接入配置
    public function wechatInterface(){

        $echoStr = isset($_GET['echostr']) ? $_GET['echostr'] : '';

        if(empty($echoStr)){
            $this->response();
        }else{
            $this->wechat->valid();
        }

    }
    /*
    验证成功后，捕捉粉丝的消息，并且回复
*/
    public function response(){
        /*
         * 先接收粉丝发来的是什么消息
         * 使用 $GLOBALS["HTTP_RAW_POST_DATA"] php内置的获取请求数据中的XML数据
         */
        $fensMsg = $GLOBALS["HTTP_RAW_POST_DATA"];

        file_put_contents("wechat.txt",$fensMsg,FILE_APPEND);

        /*
         * 接收到的粉丝消息数据是以XML格式获取的
         * 由于php中，对数据的操作最便捷，所以php中很习惯的将数据转换成数组来处理
         * php 内置的 libxml 扩展，能够将XML字符串数据转换成数组形式操作
         */
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($fensMsg,'SimpleXMLElement',LIBXML_NOCDATA);

        //file_put_contents('text.txt',$tulingMsgJson);
        /*
         * CDATA ：如果在XML中有涉及文字或字符串要用CDATA包裹起来
         * ToUserName ：要发给谁
         * FromUserName ：谁发的
         * CreateTime ：发送的时间
         * MsgType ：发送的类型
         * Content ：发送的内容
         */
        if($postObj->MsgType == 'event' && $postObj->Event == 'CLICK'){
            if($postObj->EventKey == 'MUSIC'){
                echo '<xml>
					<ToUserName><![CDATA['.$postObj->FromUserName.']]></ToUserName>
					<FromUserName><![CDATA['.$postObj->ToUserName.']]></FromUserName>
					<CreateTime>'.time().'</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[点我干嘛]]></Content>
			  </xml>';
            }
        }else{
            //调用图灵机器人
            $tulingMsgJson = $this->curlHttp('http://www.tuling123.com/openapi/api?key=32ff4a150be04ebaaf0c1a8707a64df9&inif='.$postObj->Content,'GET',[]);
            echo '<xml>
					<ToUserName><![CDATA['.$postObj->FromUserName.']]></ToUserName>
					<FromUserName><![CDATA['.$postObj->ToUserName.']]></FromUserName>
					<CreateTime>'.time().'</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA['.$tulingMsgJson['text'].']]></Content>
			  </xml>';
        }

    }

    public function curlHttp($url,$method,$data){
        $ch = curl_init(); //初始化一个curl对象
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$method);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        $res = json_decode(curl_exec($ch),true);
        curl_close($ch);
        return $res;

    }
    //获取access_token。access_token是公众号的全局唯一接口调用凭据，公众号调用各接口时都需使用access_token
    public function getAccessToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APPID."&secret=".APPSECRET;
        $method = "GET";
        $data = [];
        $accessTokenArr = $this->curlHttp($url,$method,$data);
        return $accessTokenArr;
    }

    //自定义菜单
    public function addMenu(){
        $accessTokenArr = $this->getAccessToken();
        $accessToken = $accessTokenArr["access_token"];
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;
        $method = "POST";
        $data = '{
                     "button":[
                     {
                          "type":"scancode_push",
                          "name":"扫一扫",
                          "key":"sao"
                     },
                     {
                           "name":"菜单",
                           "sub_button":[
                           {
                               "type":"view",
                               "name":"百度",
                               "url":"http://www.baidu.com/"
                            },
                            {
                               "type":"pic_sysphoto",
                               "name":"拍照",
                               "key":"paizhao"
                            }]
                     }]
                }';
        $addMenu = $this->curlHttp($url,$method,$data);
        var_dump($addMenu);
    }
}