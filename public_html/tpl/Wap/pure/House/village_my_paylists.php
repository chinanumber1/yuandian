<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>缴费订单列表</title>
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
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
		<style type="text/css">
		.village_my nav.order_list section p{ padding-left:0}
		.scrollNav{top: 0;left: 0;width: 100%}
		.scrollNav ul li{float: left;width: 65px;text-align: center;white-space: nowrap;overflow: hidden}
		.scrollNav ul li a{color: #333;font-size: 14px;line-height:40px;display: block;text-decoration: none;}
		.scrollNav ul li.on a{color: #49B44F}
		#plus{
			width:50px;
			height:50px;
			position: fixed;
			top: 80%;
			float: right;
			left: 82%;
		}
		#plus img{
			width:100%;
			height:100%;
		}
		.scrollNav ul li {
		    float: left;
		    width: 70px;
		    text-align: center;
		    white-space: nowrap;
		    overflow: hidden;
		}
		</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>缴费订单列表<div id="plus" onclick="location.href='{pigcms{:U('House/village_my_pay',array('village_id'=>$now_village['village_id']))}'"><img src="{pigcms{$static_path}images/new_my/recharge.png" /></div></header>
    </if>
		<div id="container">
		
	
		
		
<div id="scroller" class="village_my">
				
    <section class="pagesMain">

        <section class="navThis pr">

            <nav class="scrollNav pa" id="scrollerBox">

                <div class="scrollerIn" >
                    <ul class="clearfix">
                        <li  data-link="{pigcms{:U('ajax_village_my_paylists',array('village_id'=>$_GET['village_id']))}" <if condition='!$_GET["order_type"]'>class="on"</if>>
                            <a>全部</a>
                        </li>
						<foreach name='pay_type_list' item='pay_info' key='key'>
							<li data-link="{pigcms{:U('ajax_village_my_paylists',array('order_type'=>$key,'village_id'=>$_GET['village_id']))}">
								<a href="javascript:void(0)">{pigcms{$pay_info}</a>
							</li>
						</foreach>
					</ul>
                </div>

            </nav>

        </section>

        <section class="filterList">
            <ul id="data" class="mui-table-view mui-grid-view"></ul>
        </section>
</section>
				<if condition="$order_list">
					<nav class="order_list">
						<volist name="order_list" id="vo">
							<section>
                            	<p><span>缴费名称：</span><span style="color:green">{pigcms{$vo.order_name}</span><if condition='($vo["diy_type"] eq 1) && ($vo["status"] == 0)'>&nbsp;&nbsp;<input type="button" value="确认领取" onclick="check_receive({pigcms{$vo['order_id']})" style="color:red" /><elseif condition='($vo["diy_type"] eq 1) && ($vo["status"] == 1)' />&nbsp;&nbsp;<input type="button" value="已确认" /></if></p>
                                <p><span>缴费金额：</span>￥{pigcms{$vo.money|sprintf("%1\$.2f",floatval=###)}</p>
								<p><span>缴费时间：</span>{pigcms{$vo.time|date='Y-m-d H:i',###}</p>
								<if condition='$vo["order_type"] eq "property"'>
									<if condition='$vo["property_month_num"]'><p><span>物业周期：</span>{pigcms{$vo['property_month_num']}个月</p></if>
									<p><span>赠送周期：</span><if condition='$vo["presented_property_month_num"]'>{pigcms{$vo['presented_property_month_num']}<else />0</if>个月</p>
									<p><span>服务时间：</span>{pigcms{$vo['start_time']|date='Y年/m月/d日',###} 至 {pigcms{$vo['end_time']|date='Y年/m月/d日',###}</p>
									<if condition='$vo["house_size"]'><p><span>房屋面积：</span>{pigcms{$vo.house_size} ㎡</p></if>
									<if condition='$vo["property_fee"]'><p><span>物业单价：</span>{pigcms{$vo.property_fee} 元/平方米/月</p></if>
									<if condition='$vo["floor_type_name"]'><p><span>房屋类型：</span>{pigcms{$vo.floor_type_name}</p></if>
								</if>
								
								<if condition='$vo["diy_type"] eq 1'>
									<p><span>内容：</span>{pigcms{$vo.diy_content}</p>
								</if>
                            </section>
						</volist>
					</nav>
				<else/>
					<div class="noMoreDiv" style="margin-top:20px;background:#ebebeb;">您还没有使用缴费功能</div>
				</if>
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
			</div>
		</div>
			 <if condition="$is_app_browser">
			<div id="plus" onclick="location.href='{pigcms{:U('House/village_my_pay',array('village_id'=>$now_village['village_id']))}'"><img src="{pigcms{$static_path}images/new_my/recharge.png" /></div>
			</if>
<script type="text/javascript" language="javascript">
	$(function(){
		var scrollerIn= $(".navThis .scrollerIn");
		var navL=$(".scrollNav ul li");
		var len=navL.length;
		var w=navL.width();
		scrollerIn.width(w*len+40);
		var myScroll;
		myScroll = new IScroll('#scrollerBox', { scrollX: true, scrollY: false, mouseWheel: true,click:false });
	});
	
	function check_receive(order_id){
		if(!order_id){
			alert('传递参数有误！~~~~');
			return false;
		}
		
		var url = ajax_check_receive_url = "{pigcms{:U('ajax_check_receive')}";
		$.post(url,{'order_id':order_id},function(data){
			layer.open({
				content: data.msg,
				btn: ['确定'],
				shadeClose: false,
				yes: function(){
					location.reload();
				}
			});
			
		},'json')
	}
	
	$('#scrollerBox ul li').click(function(){
			$('#scrollerBox ul li').each(function(){
				$(this).removeClass('on');
			});
			
			$(this).addClass('on');
		
		
			var link = $(this).data('link');
			$.get(link,function(data){
				if(data['status']){
					var shtml='';
					for(var i in data['order_list']){
						shtml += '<section><p><span>缴费名称：</span><span style="color:green">'+data['order_list'][i]['order_name']+'</span>';
						
						if((data['order_list'][i]['diy_type'] == 1) && (data['order_list'][i]['status'] == 0)){
							shtml += '&nbsp;&nbsp;<input type="button" value="确认领取" onclick="check_receive('+data['order_list'][i]['order_id']+')" style="color:red" />'
						}else if((data['order_list'][i]['diy_type'] == 1) && (data['order_list'][i]['status'] == 1)){
							shtml += '&nbsp;&nbsp;<input type="button" value="已确认" />';
						}
						
						shtml +='</p><p><span>缴费金额：</span>￥'+data['order_list'][i]['money']+'</p><p><span>缴费时间：</span>'+data['order_list'][i]['time']+'</p>';
						if(data['order_list'][i]['order_type'] == 'property'){
							
							if(data['order_list'][i]['property_month_num']){
								shtml += '<p><span>物业周期：</span>'+data['order_list'][i]['property_month_num']+'个月</p>';
							}
							
							if(data['order_list'][i]['presented_property_month_num']){
								shtml += '<p><span>赠送周期：</span>'+data['order_list'][i]['presented_property_month_num']+'个月</p>';
							}
							shtml += '<p><span>服务时间：</span>'+data['order_list'][i]['start_time']+' 至 '+data['order_list'][i]['end_time']+'</p>';
							
							if(data['order_list'][i]['house_size']){
								shtml += '<p><span>房屋面积：</span>'+data["order_list"][i]["house_size"]+' ㎡</p>';
							}
							
							if(data['order_list'][i]['property_fee']){
								shtml += '<p><span>物业单价：</span>'+data["order_list"][i]["property_fee"]+' 元/平方米/月</p>';
							}
							
							if(data['order_list'][i]['floor_type_name']){
								shtml += '<p><span>房屋类型：</span>'+data["order_list"][i]["floor_type_name"]+'</p>';
							}
							
							if(data['order_list'][i]['diy_type'] == 1){
								shtml += '<p><span>内容：</span>' + data['order_list'][i]['diy_content'] + '</p>'
							}
							
						}
						shtml+='</section>';
					}
					$('.order_list').html(shtml);
				}else{
					$('.order_list').html('<section><p style="text-align:center"><span>暂无信息</span></p></section>');
				}
			},'json')
		});
</script>
		{pigcms{$shareScript}
	</body>
</html>