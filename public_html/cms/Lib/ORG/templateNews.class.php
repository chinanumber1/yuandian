<?php
class templateNews{
	

	public $thisWxUser;
	
	public $appid;
	
	public $appsecret;

	public function __construct($appid = null, $appsecret = null){
		
		$this->appid = $appid;
		$this->appsecret = $appsecret;
	}

    public function sendTempMsg($tempKey, $dataArr, $mer_id = 0)
    {
        $dbinfo = M('Tempmsg')->where(array('tempkey' => $tempKey, 'mer_id' => '0'))->find();
    
        if ($tempKey == 'TM00356') {
			if (! ($dbinfo['status'] && $dbinfo['tempid'])) {
                if (($dbinfo = M('Tempmsg')->where(array('tempkey' => 'OPENTM405486394'))->find()) && $dbinfo['status'] && $dbinfo['tempid']) {
                    $tempKey = 'OPENTM405486394';
                    $dataArr['keyword1'] = '工作提醒';
                    $dataArr['keyword2'] = date('Y-m-d H:i');
                    $dataArr['keyword3'] = $dataArr['work'];
					unset($dataArr['work']);
                } elseif (($dbinfo = M('Tempmsg')->where(array('tempkey' => 'OPENTM406638907'))->find()) && $dbinfo['status'] && $dbinfo['tempid']) {
                    $tempKey = 'OPENTM406638907';
                    $dataArr['keyword1'] = '工作提醒';
                    $dataArr['keyword2'] = date('Y-m-d H:i');
					unset($dataArr['work']);
                }
            }	
        }
		if ($tempKey == 'TM00251') {
            if (!($dbinfo['status'] && $dbinfo['tempid'])){
                if (($dbinfo = M('Tempmsg')->where(array('tempkey' => 'OPENTM200772305', 'mer_id' => $mer_id))->find()) && $dbinfo['status'] && $dbinfo['tempid']) {
                    $tempKey = 'OPENTM200772305';
					$dataArr['keyword1'] = $dataArr['toName'];
					$dataArr['keyword2'] = $dataArr['gift'];
					$dataArr['keyword3'] = $dataArr['time'];
					unset($dataArr['toName'],$dataArr['gift'],$dataArr['time']);
                }
            }
        }
        if ($dbinfo['status']) {
            $data = $this->getData($tempKey, $dataArr, $dbinfo['textcolor']);
            $sendData = '{"touser":"' . $dataArr["wecha_id"] . '","template_id":"' . $dbinfo["tempid"] . '","url":"' . $dataArr["href"] . '",';
			if(C('config.pay_wxapp_important') == '1'){
			$sendData.= '"miniprogram":{"appid":"'.C('config.pay_wxapp_appid').'","pagepath":"pages/index/index?redirect=webview&webview_url='.urlencode($dataArr["href"]).'"},';
			}
			$sendData.= '"topcolor":"' . $dbinfo["topcolor"] . '","data":' . $data . '}';
            $msg_class = new plan_msg();
            $param = array(
                'type' => '2',
                'mer_id' => 0,
                'content' => array('mer_id' => 0, 'content' => $sendData)
            );
            $msg_class->addTask($param);
        }
			
		if(!$mer_id){
			return true;
		}
		$tempMsg = M('Tempmsg')->field(true)->where(array('mer_id' => $mer_id, 'tempkey' => $tempKey))->find();
		
		if ($tempKey == 'TM00251') {
			if (!($tempMsg['status'] && $tempMsg['tempid'])){
				$tempKey = 'OPENTM200772305';
				if (($tempMsg = M('Tempmsg')->where(array('tempkey' => $tempKey, 'mer_id' => $mer_id))->find()) && $tempMsg['status'] && $tempMsg['tempid']) {
					$dataArr['keyword1'] = $dataArr['toName'];
					$dataArr['keyword2'] = $dataArr['gift'];
					$dataArr['keyword3'] = $dataArr['time'];
					unset($dataArr['toName'],$dataArr['gift'],$dataArr['time']);
				}
				$data = $this->getData($tempKey, $dataArr, $tempMsg['textcolor']);
			} else {
                $data = $this->getData($tempKey, $dataArr, $tempMsg['textcolor']);
            }
		} elseif($tempMsg)  {
            $data = $this->getData($tempKey, $dataArr, $tempMsg['textcolor']);
        }

        if ($mer_id && $tempMsg) {
            if ($user = M('User')->field('uid')->where(array('openid' => $dataArr["wecha_id"]))->find()) {
                if (($bindUer = M('Weixin_bind_user')->field(true)->where(array('uid' => $user['uid'], 'mer_id' => $mer_id))->find()) && $bindUer['openid']) {
                    $sendData = '{"touser":"' . $bindUer['openid'] . '","template_id":"' . $tempMsg["tempid"] . '","url":"' . $dataArr["href"] . '","topcolor":"' . $tempMsg["topcolor"] . '","data":' . $data . '}';
                    $param = array(
                        'type' => '2',
                        'mer_id' => $mer_id,
                        'content' => array('mer_id' => $mer_id, 'content' => $sendData)
                    );
                    $msg_class = new plan_msg();
                    $msg_class->addTask($param);
                }
            }
        }
		return true;
    }
	
	public function sendWeixinTempMsg($sendData, $mer_id = 0)
    {
        if ($mer_id) {
            $token_data = D('Weixin_bind')->get_access_token($mer_id);
            if ($token_data['errcode']) return '获取access_token发生错误：' . $token_data['errmsg'];
            $access_token = $token_data['access_token'];
        } else {
			$sendDataArr = json_decode($sendData,true);
			$nowUser = D('User')->get_user($sendDataArr['touser'],'openid');
			
			fdump($sendDataArr,'wxapp_msg',true);
			fdump($nowUser,'wxapp_msg',true);
			
			if(in_array(C('config.top_domain'),array('pigcms.com','group.com'))){
				$nowUser['is_follow'] = 0;
			}
			if($nowUser && $nowUser['wxapp_openid'] && !$nowUser['is_follow']){
				$tempmsg = M('Tempmsg')->where(array('tempid'=>$sendDataArr['template_id']))->find();
				fdump($tempmsg,'wxapp_msg',true);
				if($tempmsg['wxapp_tempid']){
					$formidArr = D('Wxapp_formid')->get($nowUser['uid']);
					fdump($formidArr,'wxapp_msg',true);
					if($formidArr && $formidArr['formid']){
						$this->sendWeixinWxappMsg($tempmsg,$sendDataArr,$nowUser,$formidArr['formid']);
					}
				}
			}
			
			$access_token_array = D('Access_token_expires')->get_access_token();
            if ($access_token_array['errcode']) {
                return '获取access_token发生错误：错误代码' . $access_token_array['errcode'] . ',微信返回错误信息：' . $access_token_array['errmsg'];
            }
            $access_token = $access_token_array['access_token'];
        }
        $requestUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
		
		$result = $this->postCurl($requestUrl, $sendData);
		fdump($result,'result');
    }
	
	public function sendWeixinWxappMsg($tempmsg,$sendDataArr,$nowUser,$formid){
		$access_token_array = D('Access_token_wxapp_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			return array('errcode'=>1,'errmsg'=>'获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
		}
		$access_token = $access_token_array['access_token'];
		
		$postData['touser'] = $nowUser['wxapp_openid'];
		$postData['template_id'] = $tempmsg['wxapp_tempid'];
		$postData['page'] = 'pages/index/index?redirect=webview&webview_url='.urlencode($sendDataArr['url']);
		$postData['form_id'] = $formid;
		foreach($sendDataArr['data'] as $key=>$value){
			$sendDataArr['data'][$key]['value'] = str_replace(array("\n",'\n',PHP_EOL),'',$sendDataArr['data'][$key]['value']);
		}
		if($tempmsg['wxapp_id'] == 'AT1168'){
			$postData['data'] = array(
				'keyword1' => array(
					'value'	=> $sendDataArr['data']['keyword1']['value'],
				),
				'keyword2' => array(
					'value'	=> $sendDataArr['data']['keyword2']['value'],
				),
				'keyword3' => array(
					'value'	=> $sendDataArr['data']['keyword3']['value'],
				),
				'keyword4' => array(
					'value'	=> $sendDataArr['data']['remark']['value'],
				),
			);
		}else if($tempmsg['wxapp_id'] == 'AT0009'){
			$keyword5Arr = explode('您的消费码：',$sendDataArr['data']['remark']['value']);
			if(count($keyword5Arr) == 2){
				$keyword5 = $keyword5Arr[1];
			}
			$postData['data'] = array(
				'keyword1' => array(
					'value'	=> $sendDataArr['data']['keyword2']['value'],
				),
				'keyword2' => array(
					'value'	=> $sendDataArr['data']['keyword1']['value'],
				),
				'keyword3' => array(
					'value'	=> $sendDataArr['data']['keyword3']['value'],
				),
				'keyword4' => array(
					'value'	=> $sendDataArr['data']['keyword4']['value'],
				),
				'keyword5' => array(
					'value'	=> $keyword5 ? $keyword5 : '无需消费密码',
				),
				'keyword6' => array(
					'value'	=> $keyword5 ? '消费时向商家出示消费密码，请在'.C('config.group_alias_name').'有效期内进行消费,如有疑问请致电商家' : '点击查看订单详情',
				),
			);
			$postData['emphasis_keyword'] = 'keyword3.DATA';
		}else if($tempmsg['wxapp_id'] == 'AT0218'){
			$postData['data'] = array(
				'keyword1' => array(
					'value'	=> $sendDataArr['data']['keyword1']['value'],
				),
				'keyword2' => array(
					'value'	=> $sendDataArr['data']['keyword3']['value'],
				),
				'keyword3' => array(
					'value'	=> $sendDataArr['data']['first']['value'],
				),
				'keyword4' => array(
					'value'	=> $sendDataArr['data']['remark']['value'],
				),
			);
			if(msubstr($postData['data']['keyword1']['value'],0,6) == $postData['data']['keyword1']['value']){
				$postData['emphasis_keyword'] = 'keyword1.DATA';
			}
		}else if($tempmsg['wxapp_id'] == 'AT0202'){
			$postData['data'] = array(
				'keyword1' => array(
					'value'	=> $sendDataArr['data']['first']['value'],
				),
				'keyword2' => array(
					'value'	=> $sendDataArr['data']['OrderSn']['value'],
				),
				'keyword3' => array(
					'value'	=> $sendDataArr['data']['OrderStatus']['value'],
				),
				'keyword4' => array(
					'value'	=> $sendDataArr['data']['remark']['value'],
				),
			);
			if(msubstr($postData['data']['keyword3']['value'],0,6) == $postData['data']['keyword3']['value']){
				$postData['emphasis_keyword'] = 'keyword3.DATA';
			}
		}else if($tempmsg['wxapp_id'] == 'AT0157'){
			$postData['data'] = array(
				'keyword1' => array(
					'value'	=> $sendDataArr['data']['first']['value'],
				),
				'keyword2' => array(
					'value'	=> $sendDataArr['data']['keyword1']['value'],
				),
				'keyword3' => array(
					'value'	=> $sendDataArr['data']['keyword2']['value'],
				),
				'keyword4' => array(
					'value'	=> $sendDataArr['data']['keyword3']['value'],
				),
				'keyword5' => array(
					'value'	=> $sendDataArr['data']['keyword4']['value'],
				),
			);
			$postData['emphasis_keyword'] = 'keyword4.DATA';
		}
		
		fdump($postData,'wxapp_msg',true);
		if($postData['data']){
			$requestUrl = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;
			$result = $this->postCurl($requestUrl, json_encode($postData));
			D('Wxapp_formid')->del($formid);
			fdump($result,'wxapp_msg',true);
			if($result['rt']){
				exit();
			}
		}
	}

// Get Data.data
	public function getData($key, $dataArr, $color)
    {
        // $tempsArr = $this->templates();
        
        // $data = $tempsArr["$key"]['vars'];
        // $data = array_flip($data);
        unset($dataArr['wecha_id'], $dataArr['href']);
        $jsonData = '';
        foreach ($dataArr as $k => $v) {
//             if (in_array($k, array_flip($data))) {
            $jsonData .= '"' . $k . '":{"value":"' . $v . '","color":"' . $color . '"},';
//             }
        }
        
        $jsonData = rtrim($jsonData, ',');
        
        return "{" . $jsonData . "}";
    }

	
	public function templates(){
		return array(
            'OPENTM201752540' => array(
				'cat_id'  => '2',
                'name'    => '订单支付成功通知',
                'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3', 'keyword4', 'remark'),
                'content' => '
{{first.DATA}}
订单商品：{{keyword1.DATA}}
订单编号：{{keyword2.DATA}}
支付金额：{{keyword3.DATA}}
支付时间：{{keyword4.DATA}}
{{remark.DATA}}',
				'wxapp_id'	=> 'AT0009',
				'wxapp_content' =>'
单号 {{keyword1.DATA}}
物品名称 {{keyword2.DATA}}
支付金额 {{keyword3.DATA}}
支付时间 {{keyword4.DATA}}
消费密码 {{keyword5.DATA}}
备注 {{keyword6.DATA}}',
			),
            'OPENTM201682460' => array(
				'cat_id'  => '1',
                'name'    => '订单生成通知',
                'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3', 'keyword4', 'remark'),
                'content' => '
{{first.DATA}}
时间：{{keyword1.DATA}}
商品名称：{{keyword2.DATA}}
订单号：{{keyword3.DATA}}
{{remark.DATA}}',
				'wxapp_id'	=> 'AT1168',
				'wxapp_content' =>'
生成时间 {{keyword1.DATA}}
商品信息 {{keyword2.DATA}}
订单编号 {{keyword3.DATA}}
备注 {{keyword4.DATA}}'
			),
            'TM00356' => array(
				'cat_id'  => '3',
                'name'    => '待办工作提醒',
                'vars'    => array('first', 'work', 'remark'),
                'content' => '
{{first.DATA}}
待办工作：{{work.DATA}}
{{remark.DATA}}'),
            'OPENTM202521011' => array(
				'cat_id'  => '4',
                'name'    => '订单完成通知',
                'vars'    => array('first', 'keyword1', 'keyword2', 'remark'),
                'content' => '
{{first.DATA}}
订单号：{{keyword1.DATA}}
完成时间：{{keyword2.DATA}}
{{remark.DATA}}'),
            'TM00785' => array(
				'cat_id'  => '5',
                'name'    => '开奖结果通知',
                'vars'    => array('first', 'program', 'result', 'remark'),
                'content' => '
{{first.DATA}}
开奖项目：{{program.DATA}}
中奖情况：{{result.DATA}}
{{remark.DATA}}'),
			'TM01008' => array(
				'cat_id'  => '6',
                'name'    => '缴费提醒通知',
                'vars'    => array('first', 'keynote1', 'keynote2', 'remark'),
                'content' => '
{{first.DATA}}
收费单位：{{keynote1.DATA}}
缴费账号：{{keynote2.DATA}}
{{remark.DATA}}'),
			'OPENTM201812627' => array(
				'cat_id'  => '8',
                'name'    => '佣金提醒',
                'vars'    => array('first', 'keyword1', 'keyword2','remark'),
                'content' => '
{{first.DATA}}
佣金金额：{{keyword1.DATA}}
时间：{{keyword2.DATA}}
{{remark.DATA}}'
			),
			'TM00017' => array(
				'cat_id'  => '9',
                'name'    => '订单状态更新',
                'vars'    => array('first', 'OrderSn', 'OrderStatus','remark'),
                'content' => '
{{first.DATA}}
订单编号：{{OrderSn.DATA}}
订单状态：{{OrderStatus.DATA}}
{{remark.DATA}}',
				'wxapp_id'	=> 'AT0202',
				'wxapp_content' =>'
提示 {{keyword1.DATA}}
订单号 {{keyword2.DATA}}
订单状态 {{keyword3.DATA}}
备注 {{keyword4.DATA}}'
			),
			'OPENTM405486394' => array(
				'cat_id'  => '3',
                'name'    => '待办工作提醒',
                'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3', 'remark'),
                'content' => '
{{first.DATA}}
待办名称：{{keyword1.DATA}}
消息时间：{{keyword2.DATA}}
待办内容：{{keyword3.DATA}}
{{remark.DATA}}'
			),
			'OPENTM200964573' => array(
				'cat_id'  => '11',
                'name'    => '会员卡领取通知',
                'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3','keyword4', 'remark'),
                'content' => '
{{first.DATA}}
会员编号：{{keyword1.DATA}}
会员姓名：{{keyword2.DATA}}
会员电话：{{keyword3.DATA}}
申请时间：{{keyword4.DATA}}
{{remark.DATA}}'
			),
			'TM00251' => array(
				'cat_id'  => '12',
                'name'    => '领取成功通知(领取优惠券)',
                'vars'    => array('first', 'toName', 'gift', 'time', 'remark'),
                'content' => '
{{first.DATA}}
领取人：{{toName.DATA}}
赠品：{{gift.DATA}}
领取时间：{{time.DATA}}
{{remark.DATA}}'
			),
			'OPENTM200772305' => array(
				'cat_id'  => '12',
                'name'    => '礼品领取成功通知(领取优惠券)',
                'vars'    => array('first', 'toName', 'gift', 'time', 'remark'),
                'content' => '
{{first.DATA}}
领取人：{{keyword1.DATA}}
礼品：{{keyword2.DATA}}
领取时间：{{keyword3.DATA}}
{{remark.DATA}}'
			),
			'OPENTM205984119' => array(
				'cat_id'  => '13',
                'name'    => '排号提醒通知',
                'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3', 'remark'),
                'content' => '
{{first.DATA}}
队列号：{{keyword1.DATA}}
取号时间：{{keyword2.DATA}}
等待人数：{{keyword3.DATA}}
{{remark.DATA}}'
			),
			'OPENTM406638907' => array(
				'cat_id'  => '3',
				'name'    => '待办事项通知',
				'vars'    => array('first', 'keyword1', 'keyword2', 'remark'),
				'content' => '
{{first.DATA}}
待办事项：{{keyword1.DATA}}
提醒时间：{{keyword2.DATA}}
{{remark.DATA}}'
			),
'OPENTM402026291' => array(
		'cat_id'  => '14',
		'name'    => '收款成功',
		'vars'    => array('first', 'keyword1', 'keyword2','keyword3','keyword4','keyword5', 'remark'),
		'content' => '
{{first.DATA}}
费用类型：{{keyword1.DATA}}
费用金额：{{keyword2.DATA}}
消费门店：{{keyword3.DATA}}
消费时间：{{keyword4.DATA}}
订单编号：{{keyword5.DATA}}
{{remark.DATA}}'
				),
				'OPENTM414860535' => array(
						'cat_id'  => '16',
						'name'    => '挪车提醒',
						'vars'    => array('first', 'keyword1', 'keyword2', 'remark'),
						'content' => '
{{first.DATA}}
车牌号：{{keynote1.DATA}}
发送时间：{{keynote2.DATA}}
{{remark.DATA}}'
				),
				'OPENTM408101810' => array(
						'cat_id'  => '17',
						'name'    => '来访通知',
						'vars'    => array('first', 'keyword1', 'keyword2','keyword3', 'remark'),
						'content' => '
{{first.DATA}}
访客名称：{{keynote1.DATA}}
访客电话：{{keynote2.DATA}}
来访时间：{{keynote3.DATA}}
{{remark.DATA}}'
				),
				'OPENTM413117639' => array(
						'cat_id'  => '18',
						'name'    => '工作分配通知',
						'vars'    => array('first', 'keyword1', 'keyword2', 'remark'),
						'content' => '
{{first.DATA}}
工作内容：{{keynote1.DATA}}
创建时间：{{keynote2.DATA}}
{{remark.DATA}}'
				),
				'OPENTM412319702' => array(
						'cat_id'  => '19',
						'name'    => '提现审核提醒',
						'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3', 'remark'),
						'content' => '
{{first.DATA}}
申请人：{{keynote1.DATA}}
申请时间：{{keynote2.DATA}}
提现金额：{{keynote3.DATA}}
{{remark.DATA}}'
				),'OPENTM202425107' => array(
						'cat_id'  => '19',
						'name'    => '提现审核结果通知',
						'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3','keyword4','keyword5', 'remark'),
						'content' => '
{{first.DATA}}
提现金额：{{keyword1.DATA}}
提现方式：{{keyword2.DATA}}
申请时间：{{keyword3.DATA}}
审核结果：{{keyword4.DATA}}
审核时间：{{keyword5.DATA}}
{{remark.DATA}}'
				),
				'OPENTM405462911' => array(
			'cat_id'  => '20',
			'name'    => '消息发送状态提醒',
			'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3','keyword4', 'remark'),
			'content' => '
{{first.DATA}}
消息类型：{{keyword1.DATA}}
发送状态：{{keyword2.DATA}}
发送时间：{{keyword3.DATA}}
发送对象：{{keyword4.DATA}}
{{remark.DATA}}',
				'wxapp_id'	=> 'AT0218',
				'wxapp_content' =>'
项目 {{keyword1.DATA}}
时间 {{keyword2.DATA}}
温馨提示 {{keyword4.DATA}}
备注 {{keyword5.DATA}}'
				),
		'OPENTM401833445' => array(
				'cat_id'  => '21',
				'name'    => '余额变动提示',
				'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3','keyword4', 'remark'),
				'content' => '
{{first.DATA}}
变动时间：{{keyword1.DATA}}
变动类型：{{keyword2.DATA}}
变动金额：{{keyword3.DATA}}
当前余额：{{keyword4.DATA}}
{{remark.DATA}}',
				'wxapp_id'	=> 'AT0157',
				'wxapp_content' =>'
温馨提示 {{keyword1.DATA}}
变动时间 {{keyword2.DATA}}
变动原因 {{keyword3.DATA}}
变动金额 {{keyword4.DATA}}
当前余额 {{keyword5.DATA}}'
				),
				'OPENTM401300510' => array(
						'cat_id'  => '24',
						'name'    => '欠款通知',
						'vars'    => array('first', 'keyword1', 'keyword2', 'remark'),
						'content' => '
{{first.DATA}}
商家：{{keyword1.DATA}}
欠款金额：{{keyword2.DATA}}
{{remark.DATA}}'
				)
				,'OPENTM405733036' => array(
                'cat_id'  => '23',
                'name'    => '商户入驻申请通知',
                'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3', 'keyword4','remark'),
                'content' => '
{{first.DATA}}
商户名称：{{keyword1.DATA}}
所属区域：{{keyword2.DATA}}
申请时间：{{keyword3.DATA}}
申请信息：{{keyword4.DATA}}
{{remark.DATA}}'
            ),'OPENTM405627933' => array(
                'cat_id'  => '25',
                'name'    => '退款提醒',
                'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3','remark'),
                'content' => '
{{first.DATA}}
订单号：{{keyword1.DATA}}
退款金额：{{keyword2.DATA}}
时间：{{keyword3.DATA}}
{{remark.DATA}}'
            ),

		);
	}



    public function wxapp_templates(){
        return array(
            'AT0196' => array(
                'cat_id'  => '0',
                'name'    => '投票结果通知',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4'),
                'content' => '
投票标题：{{keyword1.DATA}}
投票类型：{{keyword2.DATA}}
投票发起人：{{keyword3.DATA}}
投票结果：{{keyword4.DATA}}
参与人数：{{keyword5.DATA}}'),
            'AT0052' => array(
                'cat_id'  => '0',
                'name'    => '审核通知',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4'),
                'content' => '
姓名：{{keyword1.DATA}}
申请项目：{{keyword2.DATA}}
状态：{{keyword3.DATA}}
日期：{{keyword4.DATA}}'),
            'AT0751' => array(
                'cat_id'  => '0',
                'name'    => '成员退出通知',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4'),
                'content' => '
用户昵称：{{keyword1.DATA}}
退出方式：{{keyword2.DATA}}
备注：{{keyword3.DATA}}
退出时间：{{keyword4.DATA}}'),
            'AT0322' => array(
                'cat_id'  => '0',
                'name'    => '活动创建成功提醒',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4', 'keyword5', 'keyword6'),
                'content' => '
活动名称：{{keyword1.DATA}}
活动时间：{{keyword2.DATA}}
发布人：{{keyword3.DATA}}
发布时间：{{keyword4.DATA}}
活动人数：{{keyword5.DATA}}
报名费用：{{keyword6.DATA}}'),
            'AT0130' => array(
                'cat_id'  => '0',
                'name'    => '投票创建成功通知',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4'),
                'content' => '
投票标题：{{keyword1.DATA}}
创建时间：{{keyword2.DATA}}
发起人：{{keyword3.DATA}}
投票内容：{{keyword4.DATA}}'),
            'AT1175' => array(
                'cat_id'  => '0',
                'name'    => '内容创建成功通知',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4'),
                'content' => '
主题：{{keyword1.DATA}}
内容类型：{{keyword2.DATA}}
创建人：{{keyword3.DATA}}
创建时间：{{keyword4.DATA}}'),
            'AT1454' => array(
                'cat_id'  => '0',
                'name'    => '已报名活动参加提醒',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4'),
                'content' => '
活动时间：{{keyword1.DATA}}
活动主题：{{keyword2.DATA}}
参加人员：{{keyword3.DATA}}
费用：{{keyword4.DATA}}'),
            'AT0027' => array(
                'cat_id'  => '0',
                'name'    => '报名成功通知',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4'),
                'content' => '
报名项目：{{keyword1.DATA}}
活动主题：{{keyword2.DATA}}
报名姓名：{{keyword3.DATA}}
报名时间：{{keyword4.DATA}}'),
            'AT0036' => array(
                'cat_id'  => '0',
                'name'    => '退款通知',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4'),
                'content' => '
退款类型：{{keyword1.DATA}}
退款原因：{{keyword2.DATA}}
退款金额：{{keyword3.DATA}}
退款时间：{{keyword4.DATA}}'
            ),
            'AT0225' => array(
                'cat_id'  => '0',
                'name'    => '活动取消通知',
                'vars'    => array('keyword1', 'keyword2', 'keyword3', 'keyword4', 'keyword5'),
                'content' => '
活动名称：{{keyword1.DATA}}
取消原因：{{keyword2.DATA}}
取消时间：{{keyword3.DATA}}
报名人数：{{keyword4.DATA}}
备注：{{keyword5.DATA}}'
            ),
            'AT0878' => array(
                'cat_id'  => '0',
                'name'    => '消息已送达通知',
                'vars'    => array( 'keyword1', 'keyword2', 'keyword3'),
                'content' => '
发送人：{{keyword1.DATA}}
详情：{{keyword2.DATA}}
发送时间：{{keyword3.DATA}}'
            ),

        );
    }


// Post Request// 支付方式：{{keyword4.DATA}}
	function postCurl($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if ($js['errcode']=='0'){
				return array('rt'=>true,'errorno'=>0);
			}else {
				//exit('模板消息发送失败。错误代码'.$js['errcode'].',错误信息：'.$js['errmsg']);
				return array('rt'=>false,'errorno'=>$js['errcode'],'errmsg'=>$js['errmsg']);

			}
		}
	}




// Get Access_token Request
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}



}
