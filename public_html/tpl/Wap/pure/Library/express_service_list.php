<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html style="font-size: 20px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>快递代收列表</title>
	     <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/express_service_list.css"/>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
    <script src="{pigcms{$static_path}js/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8"></script>
    <style type="text/css">


    	.express li{
    		width: 94%;
			padding: 10px 3% 10px 3%;
			display: -webkit-flex;
		    display: flex;
		    -webkit-box-pack: justify;
		    -webkit-justify-content: space-between;
		    justify-content: space-between;
		    -webkit-box-align: center;
		    -webkit-align-items: center;
		    align-items: center;
		    background: #FFFFFF;
		    border-bottom:1px solid #F5F5F5;
    	}
        .yi_parts{
            display:inline-block;
            width: 68px;
            height: 44px;
            background: none;
            background-size:20px 20px;
        }


    </style>
</head>
<body>
	<header class="mui-bar mui-bar-nav clear">
        <div href="javascript:void(0);" class="mui-pull-left return_add" id="goBackUrl"></div>
<!--	    <a href="javascript:void(0);" onClick="javascript :window.lifepasslogin.closeWebView()" id="goBackUrl" class="mui-pull-left return_add"></a>-->
	    <h1 class="mui-title">快递代收列表</h1>
	    <!-- <a href="javascript:void(0);" class="mui-pull-right yi_parts">已取件</a> -->
	    <a href="javascript:void(0);" onclick="confirm_receipt_all({pigcms{$user_session.phone},{pigcms{$_GET['village_id']})" class="mui-pull-right yi_parts">全部取件</a>
	</header>
	<div class="contanir">
		<div class="all_conent" >

			<volist name="list" id="vo">
				<div class="currency all_express">
					<p class="odd_numbers clear">
						<span class="ft">快递单号:{pigcms{$vo.express_no}</span>
						<a href="javascript:;" class="rg">
							<if condition='$vo["status"] eq 0'>
		                    	未取件
		                    <elseif condition='$vo["status"] eq 1' />
		                    	已取件（业主）
		                    <else />
		                    	已取件（社区）
		                    </if>
						</a>
					</p>
                    <if condition="$vo.fetch_code">
                        <p><span>取件码：</span>{pigcms{$vo.fetch_code}</p>
                    </if>
					<h3>{pigcms{$vo.express_name}</h3>
					<!-- <p class="pay_money">送件费 : 10元  <span>( 未支付 )</span></p> -->
					<if condition="$vo.order_info.send_time gt 0">
						<p><span>预约送件时间：</span>{pigcms{$vo.order_info.send_time|date='Y-m-d H:i',###}</p>
					</if>
					<if condition="$vo.money gt 0">
						<p><span>送件费用：</span>{pigcms{$vo.money}元<php>if($vo['order_info']['paid']){</php><font color="green">(已支付)</font><php>}else{</php></php><font color="red">(未支付)</font><php>}</php></p>
					</if>

					<if condition='$vo["memo"]'><p class="remark">备注 : {pigcms{$vo.memo}</p></if>

					<p class="arrive_time">到件时间 : {pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</p>
					<if condition='$vo["delivery_time"]'><p class="arrive_time">取件时间：{pigcms{$vo.delivery_time|date='Y-m-d H:i:s',###}</p></if>


					<if condition='($vo["status"] eq 0) && (empty($vo["order_info"]["paid"])) && ($express_config["status"] eq 1)'>
						<p class="order">
							<button onClick="location.href='{pigcms{:U('express_appoint',array('id'=>$vo['id'],'village_id'=>$_GET['village_id']))}'">预约上门送件</button>
							<button onClick="chk_express({pigcms{$vo.id})">确认取件</button>
						</p>

					<elseif condition='!empty($vo["order_info"]) && ($vo["order_info"]["paid"] eq 1) && ($vo["order_info"]["status"] eq 0)' />
						<p class="order">
							<span class="green">已在派送中</span>
							<button onClick="chk_express({pigcms{$vo.id})">确认取件</button>
						</p>

					<elseif condition="$vo['status'] eq 0" />
						<p class="order">
							<button onClick="chk_express({pigcms{$vo.id})">确认取件</button>
						</p>

					</if>

				</div>

			</volist>

			<!-- <div class="currency all_express">
				<p class="odd_numbers clear">
					<span class="ft">快递单号:165164125612649</span>
					<a href="javascript:;" class="rg">未取件</a>
				</p>
				<h3>中通快递</h3>
				<p class="pay_money">送件费 : 10元  <span>( 未支付 )</span></p>
				<p class="remark">备注 : 测试备注能不能填写更多测试备注能不能填写更多测试备注能不能填写更多测试备注能不能填写更多测试</p>
				<p class="arrive_time">到件时间 : 2017-08-25 15:03:25</p>
				<p class="order">
					<button type="button">预约取件</button>
					<button type="button">自行取件</button>

				</p>
			</div>


			<div class="currency all_express">
				<p class="odd_numbers clear">
					<span class="ft">快递单号:165164125612649</span>
					<a href="javascript:;" class="rg active">已取件 ( 业主 )</a>
				</p>
				<h3>中通快递</h3>
				<p class="pay_money">送件费 : 10元  <span>( 未支付 )</span></p>
				<p class="remark">备注 : 测试备注能不能填写更多测试备注能不能填写更多测试备注能不能填写更多测试备注能不能填写更多测试</p>
				<p class="arrive_time">到件时间 : 2017-08-25 15:03:25</p>
				<p class="arrive_time">取件时间 : 2017-08-27 15:03:25</p>
			</div>

			<div class="currency all_express">
				<p class="odd_numbers clear">
					<span class="ft">快递单号:165164125612649</span>
					<a href="javascript:;" class="rg">未取件</a>
				</p>
				<h3>中通快递</h3>
				<p class="pay_money">送件费 : 10元  <span>( 未支付 )</span></p>
				<p class="remark">备注 : 测试备注能不能填写更多测试备注能不能填写更多测试备注能不能填写更多测试备注能不能填写更多测试</p>
				<p class="arrive_time">到件时间 : 2017-08-25 15:03:25</p>
				<p class="order">
					<span>已预约 2017-09-15- 18:03分送件</span>
					<button type="button">自行取件</button>

				</p>
			</div>
			<div class="currency all_express">
				<p class="odd_numbers clear">
					<span class="ft">快递单号:165164125612649</span>
					<a href="javascript:;" class="rg active">已取件 ( 业主 )</a>
				</p>
				<h3>中通快递</h3>
				<p class="pay_money">送件费 : 10元  <span class="active">( 已支付 )</span></p>
				<p class="remark">备注 : 测试备注能不能填写更多测试备注能不能填写更多测试备注能不能填写更多测试备注能不能填写更多测试</p>
				<p class="arrive_time">到件时间 : 2017-08-25 15:03:25</p>
				<p class="arrive_time">取件时间 : 2017-08-27 15:03:25</p>
			</div> -->
		</div>


		<div class="all_yi" style="display: none;">
			<p>暂无快递信息</p>
		</div>
	</div>



<!-- 	<div class="mask">
		<div class="poppicker">
			<h5>备注</h5>
			<p>试备注能不能填写更多测试备注能不能填试备注能不能填写更多测试备注能不能填试备注能不能填写更多测试备注能不能填试备注能不能填写更多测试备注能不能填</p>
		</div>
	</div> -->


	<script type="text/javascript">
		function confirm_receipt_all(phone,village_id){
			var confirm_receipt_url = "{pigcms{:U('confirm_receipt_all')}";
			layer.open({
				content: '你确定要全部取件吗？'
				,btn: ['确定', '取消']
				,yes: function(index){
					$.post(confirm_receipt_url,{phone:phone,'village_id':village_id},function(data){
						if(data.error == 1){
							alert(data.msg);
							location.href = location.href;
						}else{
							alert(data.msg);

						}
					},'json');
				}
			});
		}


		function chk_express(id){
			layer.open({
				content: '确认取件？',
				btn: ['确定', '取消'],
				shadeClose: false,
				yes:function(){
					var url= '{pigcms{:U("express_edit")}';
					var village_id= "{pigcms{$_GET['village_id']}";
					var status = 1;
					$.post(url,{'id':id,'status':status,village_id:village_id},function(data){
						layer.open({
							content: data.msg,
							btn: ['确定'],
						});
						if(data.status == 1){
							location.reload();
						}
					},'json')

				}
			});
		}

		// if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
        //
		// 	var reg = /versioncode=(\d+),/;
		// 	var arr = reg.exec(navigator.userAgent.toLowerCase());
		// 	// outputObj(arr)
		// 	if(arr == null){
        //
		// 	}else{
		// 		var version = parseInt(arr[1]);
        //
		// 		if(version >= 50){
		// 			if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
		// 				$('#goBackUrl').click(function(){
		// 					if(version >= 100){
		// 						$('body').append('<iframe src="pigcmso2o://closeWebView" style="display:none;"></iframe>');
		// 					}else{
		// 						$('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
		// 					}
		// 					return false;
		// 				});
		// 			}else{
		// 				if(version >= 100){
		// 					// alert(version)
		// 					window.lifepasslogin.hideWebViewHeader(false);
		// 				}
		// 				$('#goBackUrl').click(function(){
		// 					if(version >= 100){
		// 						window.lifepasslogin.closeWebView();
		// 					}else{
		// 						window.lifepasslogin.webViewGoBack();
		// 					}
		// 					return false;
		// 				});
		// 			}
		// 		}
		// 	}
		// }

		function appbackmonitor(){
			if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
				$('body').append('<iframe src="pigcmso2o://closeWebView" style="display:none;"></iframe>');
			}else{
				window.lifepasslogin.closeWebView();
			}
		}
		function outputObj(obj) {
		var description = "";
		for (var i in obj) {
			description += i + " = " + obj[i] + "\n";
		}
		alert(description);
	}



        $('#goBackUrl').click(function(){
            if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
                if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
                    $('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
                }else{
                    window.lifepasslogin.webViewGoBack();
                }
            }else{
                history.go(-1);
            }
        });
	</script>
</body>
</html>





<!-- <!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>快递代收列表</title>
        </if>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
        <style type="text/css">
		p{ font-size:12px;}
        .village_my nav.order_list section p{ padding-left:0;}
		.village_my nav.order_list section p .red{ color:red}
		.village_my nav.order_list section p .green{ color:green}
        </style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>快递代收列表</header>
    </if>
		<div id="container">
			<div id="scroller" class="village_my">
				<if condition="$list">
					<nav class="order_list">
						<volist name="list" id="vo">
							<section>
                            	<p><span>快递单号：</span>{pigcms{$vo.express_no}&nbsp;&nbsp;<span>快递类型：</span>{pigcms{$vo.express_name}</p>
								<p><span>到件时间：</span>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</p>
								<if condition="$vo.order_info.send_time gt 0"><p><span>预约送件时间：</span>{pigcms{$vo.order_info.send_time|date='Y-m-d H:i',###}</p></if>
								<if condition="$vo.money gt 0"><p><span>送件费用：</span>{pigcms{$vo.money}元<php>if($vo['order_info']['paid']){</php><font color="green">(已支付)</font><php>}else{</php></php><font color="red">(未支付)</font><php>}</php></p></if>
                                <if condition='$vo["delivery_time"]'><p><span>取件时间：</span>{pigcms{$vo.delivery_time|date='Y-m-d H:i:s',###}</p></if>
                                <if condition='$vo["status"] eq 0'>
                                	<p><span>状&nbsp;&nbsp;态：</span><span class="red">未取件</span>&nbsp;&nbsp;
									
									</p>
                                <elseif condition='$vo["status"] eq 1' />
                                	<p><span>状&nbsp;&nbsp;态：</span><span class="green">已取件（业主）</span></p>
                                <else />
                                	<p><span>状&nbsp;&nbsp;态：</span><span class="green">已取件（社区）</span></p>
                                </if>
                                <if condition='$vo["memo"]'><p><span>备注：</span>{pigcms{$vo.memo}</p></if>
									
								<p>
								<if condition='($vo["status"] eq 0) && (empty($vo["order_info"]["paid"])) && ($express_config["status"] eq 1)'>
									<button onClick="location.href='{pigcms{:U('express_appoint',array('id'=>$vo['id'],'village_id'=>$_GET['village_id']))}'">预约上门送件</button>
									<button onClick="chk_express({pigcms{$vo.id})">确认取件</button>
								</p>
								
								<elseif condition='!empty($vo["order_info"]) && ($vo["order_info"]["paid"] eq 1) && ($vo["order_info"]["status"] eq 0)' />
									<p><span class="green">已在派送中</span>
									<button onClick="chk_express({pigcms{$vo.id})">确认取件</button>
								</p> 
								</if>
								
							</section>
						</volist>
					</nav>
				<else/>
					<div class="noMoreDiv" style="margin-top:20px;background:#ebebeb;">暂无快递代收数据</div>
				</if>
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
			</div>
		</div>
        <script type="text/javascript">
        	function chk_express(id){
				  console.log('sss')
					
					layer.open({
						content: '确认取件？',
						btn: ['确定', '取消'],
						shadeClose: false,
						yes:function(){									
							var url= '{pigcms{:U("express_edit")}';
							var village_id= "{pigcms{$_GET['village_id']}";
							var status = 1;
							$.post(url,{'id':id,'status':status,village_id:village_id},function(data){
								layer.open({
									content: data.msg,
									btn: ['确定'],
								});
								if(data.status == 1){
									location.reload();
								}
							},'json')
						
						}
					});
					  
				 // layer.open({
					// content: '确认取件？'
					// ,btn: ['确定', '取消']
					// ,yes: function(index){
					  
						// var url= '{pigcms{:U("express_edit")}';
						// var village_id= "{pigcms{$_GET['village_id']}";
						// var status = 1;
						// $.post(url,{'id':id,'status':status,village_id:village_id},function(data){
							// if(data.status == 1){
								// alert(data.msg);
								// location.reload();
							// }else{
								// alert(data.msg);
							// }
						// },'json')
					// }
				  // });
			}
        </script>
		{pigcms{$shareScript}
	</body>
</html> -->