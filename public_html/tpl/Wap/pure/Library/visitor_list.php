<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8" />
	<if condition="!$is_app_browser">
		<title>{pigcms{$now_village.village_name}</title>
		<else/>
		<title>访客登记列表</title>
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
	<script type="text/javascript">
        var village_id = "{pigcms{$_GET['village_id']}";
	</script>
	<style type="text/css">
		p{ font-size:12px;}
		.village_my nav.order_list section p{ padding-left:0;}
		.village_my nav.order_list section p .red{ color:red}
		.village_my nav.order_list section p .green{ color:green}
	</style>
</head>
<body>
<if condition="!$is_app_browser">
	<header class="pageSliderHide"><div id="backBtn"></div>访客登记列表</header>
</if>
<div id="container">
	<div id="scroller" class="village_my ">
		<div style='text-align:center;line-height:35px;height:35px; margin: 8px 0px;；font-size:12px'>
			<!-- <a href="{pigcms{:U('visitor_list_open',array('village_id'=>$_GET['village_id']))}"><span style='  padding: 7px 30px;background: #06c1ae;border-radius: 2px;color:#fff'>给访客开门</span></a> -->
			<if condition="$list">
				<nav class="order_list">
					<volist name="list" id="vo">
						<section>
							<p><if condition='$vo["visitor_name"]'><span>访客姓名：</span>{pigcms{$vo.visitor_name}&nbsp;&nbsp;</if><span>访客手机号：</span>{pigcms{$vo.visitor_phone}</p>
							<p><if condition='$vo["owner_name"]'><span>业主姓名：</span>{pigcms{$vo.owner_name}&nbsp;&nbsp;</if><span>业主手机号：</span>{pigcms{$vo.owner_phone}</p>
							<if condition='$vo["owner_address"]'><p><span>业主地址：</span>{pigcms{$vo.owner_address}</p></if>
							<p><span>访客类型：</span>{pigcms{$visitor_type[$vo['visitor_type']]}</p>
							<if condition='$vo["status"] eq 0'>
								<p><span>状态：</span><span class="red">未通行</span>&nbsp;&nbsp;<a href='javascript:;' class='chk_vistor' data-id='{pigcms{$vo.id}' ><span style='    background: #ddd;
    padding: 4px 4px;'>确认通行</span></a></p>
								<elseif condition='$vo["status"] eq 1'/>
								<p><span>状态：</span><span class="green">已通行（业主）</span></p>
								<else/>
								<p><span>状态：</span><span class="green">已通行（社区）</span></p>
							</if>
							<p><span>来访时间：</span>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</p>
							<if condition='$vo["pass_time"]'><p><span>放行时间：</span>{pigcms{$vo.pass_time|date='Y-m-d H:i:s',###}</p></if>
							<if condition='$vo["memo"]'><p><span>备注：</span>{pigcms{$vo.memo}</p></if>
						</section>
					</volist>
				</nav>
				<else/>
				<div class="noMoreDiv" style="margin-top:20px;background:#ebebeb;">暂无访客登记数据</div>
			</if>
			<if condition="!$is_app_browser">
				<div id="pullUp" style="bottom:-60px;">
					<img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
				</div>
			</if>
		</div>
	</div>

	<script type="text/javascript">
        $(".chk_vistor").on('click',function(){
            var id=$(this).attr('data-id')
            layer.open({
                content: '确认通行？'
                ,btn: ['确定', '取消']
                ,yes: function(){
                    var url= '{pigcms{:U("chk_visitor_info")}';
                    var status = 1;
                    $.post(url,{'id':id,'status':status},function(data){
                        if(data.status == 1){
                            layer.open({
                                content: data.msg
                                ,skin: 'msg'
                                ,time: 2
                            });
                            location.reload();
                        }else{
                            alert(data.msg);
                        }
                    },'json')
                }
            });
        })
        function chk_vistor(id){
            // if(!confirm('确认通行？')){
            // 	return;
            // }
            layer.open({
                content: '确认通行？'
                ,btn: ['确定', '取消']
                ,yes: function(){
                    var url= '{pigcms{:U("chk_visitor_info")}';
                    var status = 1;
                    $.post(url,{'id':id,'status':status},function(data){
                        if(data.status == 1){
                            layer.open({
                                content: data.msg
                                ,skin: 'msg'
                                ,time: 2
                            });
                            location.reload();
                        }else{
                            alert(data.msg);
                        }
                    },'json')
                }
            });
        }
	</script>
	{pigcms{$shareScript}
</body>





</html>