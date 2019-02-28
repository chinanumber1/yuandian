<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>立即下单</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link href="http://hf.pigcms.com/tpl/Wap/default/static/css/appoint_form.css?07" rel="stylesheet"/>

		<link rel="stylesheet" type="text/css" href="http://hf.pigcms.com/tpl/Wap/default/static/css/datePicker.css">
		<link rel="stylesheet" type="text/css" href="http://hf.pigcms.com/tpl/Wap/default/static/css/mobiscroll_min.css" media="all">
		
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/place_order.css"/>
		<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script type="text/javascript" src="http://hf.pigcms.com/tpl/Wap/default/static/js/mobiscroll_min.js"></script>

	</head>

	<style>
		/*.my_adress{position:relative;}

		.my_adress a{
			position:absolute;
			left:25%;
			top:18px;
		}
*/
		.kv-line {
		    display: -webkit-box;
		    display: -ms-flexbox;
		    margin: 1.2rem 0;
		    font-size: 14px;
		}

		.receive_time_bottom {/*取消按钮*/
		    font-size: 16px;
		    text-align: center;
		    border-top: 1px solid #e4e4e4;
		    padding:17px 0;
		}
	</style>
	<body>
		<header class="header">
			<a href="JavaScript:history.back(-1)"><i></i></a>
			<span>立即下单</span>
		</header>
		<header class="head">
			<img src="{pigcms{$service_info.listimg}"/>
			<ul>
				<li>{pigcms{$service_info.title}</li>
				<li>{pigcms{$service_info.nickname}</li>
			</ul>
		</header>

		<div class="numbers">
			<span>{pigcms{$service_info.price} 元/{pigcms{$service_info.unit}</span>
			<p><i class="jian"></i><span>1</span><b class="add"></b></p>
			<input type="hidden" name="serviceNumber" id="serviceNumber" value="1">
		</div>
		

		<!-- 收货地址 -->
		<div class="my_adress " style="height: 65px;">
			<label>地址:</label> 
			<if condition="$addressInfo">
				<a id="adress_html" style="margin-right: 5%;">
					<p><span>{pigcms{$addressInfo.adress}</span>&nbsp;<span>{pigcms{$addressInfo.detail}</span></p>
					<p><span>{pigcms{$addressInfo.name}</span>&nbsp;<span>{pigcms{$addressInfo.phone}</span></p>
				</a>
			<else/>
				<a id="adress_html"> <span class="" >请选择收货地址和联系方式</span> </a>
			</if>
			<input type="hidden" name="adress_id" id="adress_id" value="{pigcms{$addressInfo.adress_id}">
			<i class="icon_right rg"></i>
		</div>
		
		<!-- 收货地址弹框 -->
		<div id="rcv-mask" class="mask quxiao fee_show" style="display:none;"></div>
		<div id="receive_time_dialog" style="display:none;" class="receive_time_dialog">
			<div class="">
				<div id="receive_time_wrap">
					<dl style="display:none; padding: 0.28rem 1.2rem;">
						<volist name="adress_list" id="vo">
							<dd style="position:relative;" onclick='address_choose("{pigcms{$vo.adress_id}","{pigcms{$vo.name}","{pigcms{$vo.phone}","{pigcms{$vo.adress}","{pigcms{$vo.detail}")'>
					        	<div>
					                <div class="kv-line">
					                    <p>{pigcms{$vo.name}&nbsp;&nbsp;&nbsp;&nbsp;{pigcms{$vo.phone}</p>
					                    <if condition="$vo.default eq 1">
					                    	<span style="color:#06c1bb">【默认】</span>
					                    </if>
									</div>
					                <div class="kv-line">
					                    <p>{pigcms{$vo.province_txt}&nbsp;{pigcms{$vo.city_txt}&nbsp;{pigcms{$vo.area_txt}&nbsp;{pigcms{$vo.adress}&nbsp;{pigcms{$vo.detail}</p>
					                </div>
								</div>
						    </dd>
						</volist>
				    </dl>
				</div>
			</div>
			<a href="javascript:void(0);" onclick="address_url()">
				<div class="receive_time_bottom bg">管理地址</div>
			</a>
		</div>
		

		<script>
			/*选择地址点击效果*/
			$('.my_adress').click(function(){
				$('.mask').show();
        		$('#receive_time_dialog').show();
        		$('#receive_date_wrap').hide();
        		$('#receive_time_wrap dl').show().siblings('ul').hide();
        		$('.bg').show().prev('.quxiao').hide();
			});

			function address_url(){
				location.href = "{pigcms{:U('Yuedan/adress',array('rid'=>$_GET['rid']))}";
			}

			function address_choose(adress_id,name,phone,address,detail){
				$("#adress_html").html("<p><span>"+address+"</span>&nbsp;<span>"+detail+"</span></p><p><span>"+name+"</span>&nbsp;<span>"+phone+"</span></p>");
				$("#adress_id").val(adress_id);
				$('.mask').hide();
                $('.receive_time_dialog').hide();
			}

			//取消按钮点击和蒙层点击效果
			$('.quxiao').click(function(){
				$('.mask').hide();
				$('.receive_time_dialog').hide();
			});
		</script>

		<if condition="$categoryInfo['is_free'] eq 1">
			<div style="background: #fff; margin-top: 10px; width: 94%; padding: 10px 3%;">
				<p style=" margin-bottom: 10px;font-size: 17px; color: #333;">温馨提示：</p>
				<div style="color: #666; font-size: 15px; padding:1px 20px;">下单后{pigcms{$categoryInfo['cancel_time']}小时内免费取消，超过{pigcms{$categoryInfo['cancel_time']}小时系统需扣除订单总额的{pigcms{$categoryInfo['cancel_proportion']}%作为超时费用</div>
			</div>
		</if>
		<style>
			.yxc-time-con{
				    overflow-x: scroll;
			}
			.yxc-time-con .fu:after{
				content: " ";
				display: block;
				clear:both;

			}
			.yxc-time-con .fu{
				height: 52px;
				width: 150%;
				overflow:hidden; 
			}
			.yxc-time-con .fu>dl{
				width: 95px;
			}
		</style>
		<!--预约时间  -->
		<div class="yuyue">
			<span>预约时间:</span>
			<span class="timeText">请选择预约时间</span>
			<i class="icon_right rg" style="float: right;margin-top: 5px;"></i>
		</div>
		

		<section id="service-date" style="min-height: 100%;">
			<div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
				<header class="yxc-brand"><a class="arrow-wrapper" data-role="cancel"><i class="bt-brand-back"></i></a><span>选择预约时间</span></header>

				<div class="yxc-time-con number-4">
					<div class="fu">
						<volist name="daysList" id="vo">
							<dl><dt data-role="date" data-text="{pigcms{$vo.c}" class="<if condition='$key eq 0'>active</if>" data-date="{pigcms{$vo.b}">{pigcms{$vo.a}<span>{pigcms{$vo.b}</span></dt></dl>
						</volist>
					</div>
					
				</div>

				<div class="yxc-time-con" data-role="timeline">
					<volist name="timeList" id="vo">
						<div class="date-{pigcms{$key} timeline" <if condition="$key neq 0">style="display:none"</if>>
							<volist name="vo" id="vovo">
								<dl> <dd data-role="item" data-week="{pigcms{$vovo.week}" data-bespeak="{pigcms{$vovo.bespeak_time}" data-disable="{pigcms{$vovo.disable}" data-date="{pigcms{$vovo.date}" data-peroid="{pigcms{$vovo.time}" class="{pigcms{$vovo.disable}"> {pigcms{$vovo.time} <br><if condition="$vovo.disable eq ''">可预约<else/>不可预约</if></dd> </dl>
							</volist>
						</div>
					</volist>
					

				</div>

			</div>
		</section>
		
		<input type="hidden" name="bespeak_time" id="bespeak_time">
		<script>
			$('.yxc-time-con .fu').width($('.yxc-time-con .fu dl').length*95);
			$('.yuyue').click(function(e){
				$('#service-date').show();
			});

			$('.yxc-time-con .fu>dl').click(function(e){
				var index=$(this).index();
				$('.yxc-time-con>div.timeline:eq('+index+')').show().siblings('div.timeline').hide();
				$(this).find('dt').addClass('active').parent().siblings('dl').find('dt').removeClass('active');
			});

			$('.timeline dl').click(function(e){
				var time=$(this).find('dd').attr('data-peroid');
				var week=$(this).find('dd').attr('data-week');
				var bespeak=$(this).find('dd').attr('data-bespeak');
				var disable=$(this).find('dd').attr('data-disable');
				if(disable == 'disable'){
					alert('不可预约');
					return false;
				}else{
					$('.timeText').text(week +' '+ time);
					$('#bespeak_time').val(bespeak);
					$('#service-date').hide();
				}
				
			});

			$('.arrow-wrapper').click(function(e){
				$('#service-date').hide();
			});
		</script>





		<!--服务日期-->
		<div class="service_date">
			<div class="message">
				<span>备注:</span>
				<textarea name="remarks" rows="3" cols="3" id="remarks" placeholder="说说你的其他要求" maxlength="60"></textarea>
			</div>
			<p class="length"><span>0</span>/60</p>
		</div>
		<!--平台购买协议-->
		<h4><span>同意</span> 【平台购买服务协议】</h4>
		<textarea id="demo5a" style="display:none;">
			<div style="padding:20px; ">
				<div style="height:550px;overflow:auto;overflow-x:hidden;">
					<span>
						{pigcms{$agreementInfo.content}
					</span>
				</div>
				<div class="bottoss" style="text-align: center;height: 50px;background: #06C1AE;line-height: 50px;color: #fff;margin-top: 15px;">
					<a href="javascript:;"  class="" style="color: #FFFFFF;" onclick="layer.closeAll();">我要关闭！！</a>
				</div>
			</div>
		</textarea>

		<script type="text/javascript">
			$('h4').click(function(e){
				var html = demo5a.value;
				var pageii = layer.open({
					type: 1
					,content: html
					,anim: 'up'
					,style: 'position:fixed; left:0; top:0; width:100%; height:100%;'
				});
			});
		</script>

		<div style="padding-bottom: 50px;"></div>
		
		<div class="bottom1">
			<div>合计: <span>{pigcms{$service_info.price}</span>&nbsp;元</div>
			<input type="hidden" name="total_price" id="total_price" value="{pigcms{$service_info.price}">
			<div onclick="add_order()">确认下单</div>
		</div>
		
		
		<script type="text/javascript">
			$('.message textarea').keyup(function(e){
				var text=$(this).val();
				var len=text.length;
				$('.length span').text(len);
			});
			//add jian 点击事件

			$('.add').click(function(e){
				var servicePrice = "{pigcms{$service_info.price}";
				var num=$(this).prev().text();
				num++;
				$(this).prev().text(num);
				$("#serviceNumber").val(num);
				var price=parseFloat(num*servicePrice).toFixed(2);
				$("#total_price").val(price);
				$('.bottom1 div span').text(price);
			});

			$('.jian').click(function(e){
				var servicePrice = "{pigcms{$service_info.price}";
				var num=$(this).next().text();
				if(num==1){
					num=1;
				}else{
					num--;
				}
				$(this).next().text(num);
				$("#serviceNumber").val(num);
				var price=parseFloat(num*servicePrice).toFixed(2);
				$("#total_price").val(price);
				$('.bottom1 div span').text(price);
			});


			function add_order(){
				var total_price = $("#total_price").val();
				layer.open({
					content: '您确定要支付'+total_price+'元，购买此服务吗？'
					,btn: ['确定', '取消']
					,yes: function(index){
						var rid = "{pigcms{$_GET['rid']}";
						var sum = $("#serviceNumber").val();
						var remarks = $("#remarks").val();
						var adress_id = $("#adress_id").val();
						var bespeak_time = $("#bespeak_time").val();

						var place_orderUrl = "{pigcms{:U('Yuedan/place_order')}";
						$.post(place_orderUrl,{rid:rid,sum:sum,remarks:remarks,'adress_id':adress_id,'bespeak_time':bespeak_time},function(data){
							if(data.error == 1){
								layer.open({
								    content: data.msg
								    ,btn: ['确定']
								    ,yes: function(index){
								        location.href = "{pigcms{:U('Yuedan/my_order')}";
								    }
								});
							}else if(data.error == 3){
								layer.open({
								    content: data.msg
								    ,btn: ['确定']
								    ,yes: function(index){
								        location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_nextorder_'))}{pigcms{$_GET['rid']}";
								    }
								});
							}else{
								layer.open({
									content: data.msg
									,btn: ['确定']
								});
							}

						},'json');
					}
				});
			}

			
		</script>
	</body>
</html>
