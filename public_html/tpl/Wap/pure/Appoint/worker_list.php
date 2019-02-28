<!DOCTYPE html>
<html>

	<head>
		<title>选择技师</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />
	</head>
	<link rel="stylesheet" href="{pigcms{$static_path}css/common.css" />
	<link rel="stylesheet" href="{pigcms{$static_path}css/worker_list.css" />
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>

    <style>
        .tip_reward{
            display: block;
            width: 100%;
            height: 107%;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 50%;
            background-color: rgba(76, 76, 65, 0.98);
            content: attr(data-text);
            transition: all 1s ease;
            color: white;
        }
        .title_info {
            text-align: center;
            font-weight: bolder;
            font-size: 18px;
            padding: 20px 0 0;
        }

        .title_msg {
            text-align: center;
            font-size: 18px;
            padding: 20px 0;
        }

        .layermcont {
            padding: 0;
        }

        .layermchild {
            width: 66%;
        }

        .tip_info_list {
            border-top: 1px solid #f0f0f0;
            padding: 10px 20px;
        }

        .money_info {
            float: right;
        }

    </style>
	<body>
		<section class="listBox" >

		<if condition='$merchant_worker_list'>
			<volist name='merchant_worker_list' id='worker'>
			
				<dl class="dealcard">
					<dd class="link-url" data-url="#" <if condition="$worker['is_reward'] eq 2">style="padding:1rem 1rem 2.8rem;"</if>>
                        <div class="dealcard-img imgbox"  <if condition="$worker['is_reward'] eq 2">style="overflow: visible;"<else/>onclick="location.href='{pigcms{:U('worker_detail',array('merchant_worker_id'=>$worker['merchant_worker_id'],'appoint_id'=>$_GET['appoint_id']))}'"</if>>
                            <img style="width: 100%;" src="{pigcms{$config.site_url}/upload/appoint/{pigcms{$worker['avatar_path']}" alt="1213">

                            <if condition="$worker['is_reward'] eq 2">
                                <div class="tip_reward" onclick="worker_reward_pay('{pigcms{$worker.merchant_worker_id}')">
                                    <div style="margin: 30% auto;font-size: 0.2rem;width: 58%;text-align: center;">打赏后查看清晰图</div>
                                </div>
                                <div style="width: 80%;margin: 0 auto;background-color: #46d2e6;color: white;text-align: center;border-radius: 5%;" onclick="worker_reward_pay('{pigcms{$worker.merchant_worker_id}')">去打赏</div>
                            </if>
                        </div>
                        <div class="dealcard-block-right" onclick="location.href='{pigcms{:U('worker_detail',array('merchant_worker_id'=>$worker['merchant_worker_id'],'appoint_id'=>$_GET['appoint_id']))}'">
							<div class="brand"> {pigcms{$worker['name']} </div>
							<div class="title">{pigcms{$worker['desc']|html_entity_decode}</div>
							<div class="price">共服务{pigcms{$worker['finish_count']}次 </div>
						</div>
						<div class="dealcard-block-end-right">
							<a href="{pigcms{:U('order',array('merchantWorkerId'=>$worker['merchant_worker_id'],'appoint_id'=>$_GET['appoint_id']))}">选我</a>
						</div>
					</dd>
				</dl>
			</volist>
		</if>
		</section>

        <script type="text/javascript">
            var click = false;

            function worker_reward_pay(merchant_worker_id) {
                if (click) return false;
                click = true;
                setTimeout(function () {
                    if (click) {
                        console.log('change')
                        click = false;
                    }
                }, 2000);
                var worker_reward_pay_order = "{pigcms{:U('Appoint/worker_reward_pay_order')}";
                var worker_reward_pay = "{pigcms{:U('Appoint/worker_reward_pay')}";

                $.post(worker_reward_pay_order, {'merchant_worker_id': merchant_worker_id}, function (data) {
                    console.log('支付信息-》  ', data)
                    click = false;
                    if (data.error == 3) {
                        layer.open({
                            content: '<div  class="title_info">' + data.msg + '</div><br>' +
                            '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                            '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>' +
                            '<div class="tip_info_list" style="color: #FF658E;">还需充值（元）：<div class="money_info">￥' + data.info.difference + '</div></div>'
                            , btn: ['确定', '取消']
                            , yes: function (index) {
                                location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_portal_article_'))}{pigcms{$article['aid']}";
                            }
                        });
                    } else if (data.error == 1 || data.error == 2) {
                        layer.open({
                            content: '<div  class="title_msg">' + data.msg + '</div>'
                            , btn: ['确定']
                            , yes: function (index) {
                                window.location.href = window.location.href;
                            }
                        });
                    } else if (data.error == 5) {
                        layer.open({
                            content: '<div  class="title_info">打赏</div><br>' +
                            '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                            '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>'
                            , btn: ['立即支付', '取消']
                            , yes: function (index) {
                                $.post(worker_reward_pay, {'merchant_worker_id': merchant_worker_id}, function (data) {
                                    if (data.error == 3) {
                                        layer.open({
                                            content: '<div  class="title_info">' + data.msg + '</div><br>' +
                                            '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                                            '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>' +
                                            '<div class="tip_info_list" style="color: #FF658E;">还需充值（元）：<div class="money_info">￥' + data.info.difference + '</div></div>'
                                            , btn: ['确定', '取消']
                                            , yes: function (index) {
                                                location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_portal_article_'))}{pigcms{$article['aid']}";
                                            }
                                        });
                                    } else if (data.error == 1 || data.error == 2) {
                                        layer.open({
                                            content: '<div  class="title_msg">' + data.msg + '</div>'
                                            , btn: ['确定']
                                            , yes: function (index) {
                                                window.location.href = window.location.href;
                                            }
                                        });
                                    } else {
                                        layer.open({
                                            content: '<div  class="title_msg">' + data.msg + '</div>'
                                            , btn: ['确定']
                                        });
                                    }
                                }, 'json');
                            }
                        });
                    } else {
                        if (data.code == 2) {
                            layer.open({
                                content: '<div  class="title_msg">请先登录</div>'
                                , btn: ['去登录']
                                , yes: function (index) {
                                    location.href = "{pigcms{:U('Login/index')}";
                                }
                            });
                        } else {
                            layer.open({
                                content: '<div  class="title_msg">' + data.msg + '</div>'
                                , btn: ['确定']
                            });
                        }
                    }
                }, 'json');
            }
        </script>
	</body>
</html>