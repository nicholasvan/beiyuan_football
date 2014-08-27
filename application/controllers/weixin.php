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
			error_log(print_r($postObj, true));
			switch ($MsgType) {
			case 'event':
					$Event = $postObj->Event;
					if ($Event == 'subscribe') {
							$contentStr = "  欢迎您关注北苑家园足球队，您将可以在此获取球队、活动相关信息。\n\n发送\"帮助\"可以查看现有服务";
					}
					break;
			case 'text':
					$contentStr = "您发送的信息：".$keyword;
					break;
			}
	    return $contentStr;
    }

	private function parseKeyword($keyword)
	{
			$patterns = array(
					'帮助' => '查看帮助说明', 
					'注册' => '注册个人信息，输入格式: 姓名#位置1#位置2', 
					'报名' => '报名参加活动，输入格式: 报名#0824 或者 报名#待定#0824。如果不填日期，则是报名最近的活动', 
					'创建' => '创建活动，格式: 日期(月日, 例0824)#人数#时间#地点#其他说明(可不填)', 
					'活动' => '查看本周活动或者是某日活动详情，输入格式：活动 或者 活动#0824', 
					'分组' => '查看某日分组情况，输入格式：分组 或者 分组#0824',
					'数据' => '查看某日比赛的数据(暂不开放)',
			);
			foreach ($patterns as $pattern => $info) {
					$pecr = '/'.$pattern.'/';
					switch ($pattern) {
					case '帮助':
							$ret = "可以输入下列关键词查看信息：\n";
							foreach ($patterns as $p => $i) {
									$ret .= $p . ":\n";
									$ret .= $i . "\n";
							}
							break;
					case '注册':
							list($name, $pos1, $pos2) = explode('#', $keyword);
							$ret = "您的注册信息：姓名[$name]，位置1[$pos1], 位置2[$pos2]\n";
							break;
					case '报名':
							$p = '/(报名)|([\d]+)|(待定)/i';
							preg_match_all($p, $keyword, $matches);
							if ($matches) {
									if (count($matches) == 4) {

									} elseif (count($matches) == 3) {
									} elseif ($mathches[1] == '报名'){
									}
							}
							break;
					case '创建':
							break;
					case '活动':
							break;
					case '分组':
							$p = '/(分组)|([\d]+)/i';
							preg_match_all($p, $keyword, $matches);
							if (isset($matches[1])){
									$pos = array_search('分组', $matches[1]);
							} else {
							}

							if (isset($matches[2])){
									foreach ($matches[2] as $date) {
											if (is_numeric($date)) {
													break;
											}
									}
							}
							break;
					}
			}
	}
}
