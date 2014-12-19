<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Short description for weixin.php
 * @author wangkun <wangkun@wangkundeMacBook-Pro.local>
 */
class Weixin extends CI_Controller {
    public function index() {
        $echoStr = $_GET['echostr'];
        if ($echoStr) {
            if ($this->checkSignature()) {
                echo $echoStr;
                exit;
            }
        }
        $this->responseMsg();
    }


    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                the best way is to check the validity of xml by yourself */
            //libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>0</FuncFlag>
                </xml>";             
            $msgType = "text";
            //$contentStr = "您发送的信息：".$keyword;
            $contentStr = $this->getResponse($postObj);
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
        $token = 'beiyuan';
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];
                
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    //private function getResponse($keyword, $toUsername)
	private function getResponse($postObj)
    {
			$MsgType    = $postObj->MsgType;
			$toUsername = $postObj->toUsername;
			$keyword    = $postObj->Content;
			//error_log(print_r($postObj, true));
			switch ($MsgType) {
			case 'event':
					$Event = $postObj->Event;
					if ($Event == 'subscribe') {
							$contentStr = "  欢迎您关注北苑家园足球队，您将可以在此获取球队、活动相关信息。\n\n发送\"帮助\"可以查看现有服务";
					}
					break;
			case 'text':
                    $contentStr = $this->parseKeyword($keyword);
					break;
			}
	    return $contentStr;
    }

	private function parseKeyword($keyword)
	{
        $patterns = array(
            '/帮助/'             => '',
            '/(进球|助攻)#(.+)/' => "进球/助攻统计",
            '/注册#(.+)/'        => "注册：注册个人信息\n输入格式: \n注册#姓名#位置1#位置2，\n可以只注册一个位置",
            '/报名#(.+)/'        => "报名：报名参加活动\n输入格式: \n报名#0824 或者 报名#待定#0824",
            '/创建#(.+)/'        => "创建：创建活动\n输入格式: \n创建#日期(月日, 例0824)#人数#时间#地点#其他说明(可不填)",
            '/活动(.*)/'         => "活动: 查看本周活动或者是某日活动详情\n输入格式：\n活动 或者 活动#0824",
            '/分组(.*)/'         => "分组：查看某日分组情况\n输入格式：\n分组 或者 分组#0824",
            '/随机:(.*)(\d+)组/'  => '随机分组',
            '/数据/'             => '数据：查看某日比赛的数据(暂不开放)',
        );
        $ret = "输入信息找不到对应内容, 请输入‘帮助’查看更多信息";
        foreach ($patterns as $pattern => $info) {
            if (preg_match($pattern, $keyword, $matches)) {
                switch ($pattern) {
                case '/帮助/':
                    $ret = "可以输入下列关键词：\n";
                    foreach ($patterns as $p => $i) {
                        $ret .= $i . "\n\n";
                    }
                    break;
                case '/注册#(.+)/':
                    $poses = $matches[1];
                    $info_array = explode('#', $poses);
                    $name = $info_array[0];
                    $pos1 = $info_array[1];
                    if (isset($info_array[2])) {
                        $ret = "您的注册信息：姓名[$name] 第一位置[$pos1] 第二位置[$pos2]\n";
                    } else {
                        $ret = "您的注册信息：姓名[$name] 第一位置[$pos1]\n";
                    }
                    break;
                case '/报名#(.+)/':
                    break;
                case '/创建#(.+)/':
                    break;
                case '/活动(.*)/':
                    break;
                case '/分组:(.*)(\d+)组/':
                    /*
                    if ($matches[1]) {
                        $group   = trim('#', $matches[1]);
                        $g_array = explode('#', $group);
                        $date    = null;
                        if (!empty($g_array[0])) {
                            $date = $g_array[0];
                        }
                        $ret = "查看分组 日期 [$date]";
					} else {
                        $ret = "老刘，天宇，中原，小李，糊涂，周玺，丁强\nJJ，陈路，叮当，一杰，孙剑，雷总，海天\n定坤，林云，大薛，亚鹏，文捷，大山，老韩";
					}
                    break;
                     */
                case '/(进球|助攻)#(.+)/':
                    if($matches[1]){
                        $type = $matches[1];
                        $name = $matches[2];
                        $this->load->model('weixinm');
                        $r_num = $this->weixinm->recordStatic($type, $name);
                        $ret = "$name $type 总数:$r_num";
                    }
                    break;
                case '/数据/':
                    $this->load->model('weixinm');
                    $ret = $this->weixinm->total();
                    break;
                case '/随机:(.*)(\d+)组/':
                    $ret = "";
                    $matches[1] = str_replace("：", ":", $matches[1]);
                    $matches[1] = str_replace("，", ",", $matches[1]);
                    $members = trim($matches[1]);
                    $members_list = explode(',', $members);
                    shuffle($members_list);
                    $teams   = $matches[2] ? $matches[2] : 3;
                    $final_player = array_chunk($members_list, ceil(count($members_list) / $teams));
                    foreach ($final_player as $t => $mm) {
                        $ret .= "分组".($t+1).":";
                        foreach ($mm as $name) {
                            $ret .= "$name ";
                        }
                        $ret .= "\n";
                    }
                    break;
                }
            }
        }
		return $ret;
    }

    public function test(){
        $type = 'assis';
        $name = '文捷';
        $this->load->database();
        echo "test";
    }
}
