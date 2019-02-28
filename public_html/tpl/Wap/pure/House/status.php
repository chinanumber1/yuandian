<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>任务详情</title>
</head>
<body class=" hIphone" style="padding-bottom: initial;background: #ecedf1;">
<div id="fis_elm__0"></div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/lib_3a812b5.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/style_dd39d16.css">
<!-- <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/orderhistory_c6670c7.css"> -->
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/order_4bc7e9e.css">
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<style>
.upload_item {
    float: left;
    position: relative;
    display: -webkit-box;
    -webkit-box-pack: center;
    -webkit-box-align: center;
    border: solid 5px #fff;
    -webkit-box-sizing: border-box;
    overflow: hidden;
}
</style>
<div id="fis_elm__1"></div>
<img src="{pigcms{$static_path}shop/images/hm.gif" width="0" height="0" style="display:block">
<div id="container">
    <div id="fis_elm__2">
        <div id="common-widget-nav" class="common-widget-nav ">
            <div class="left-slogan"> <a class="left-arrow icon-arrow-left2" data-node="navBack" href="{pigcms{$back_url}"></a> </div>
            <div class="center-title"> <a href="javascript:void(0)">{pigcms{$title}详情</a> </div>
            <div class="right-slogan "></div>
        </div>
    </div>
    <div id="fis_elm__4">
        <div id="order-widget-orderhistory" class="order-widget-orderhistory">
            <div data-node="timeLine" class="timeline"></div>
            <div class="relative-wrapper">
            <volist name="logs" id="vo">
                <div class="item">
                    <div class="status-icon">
                    	<span class="-mark">
                    		<if condition="$vo['status'] eq 0"><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 1"/><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 2"/><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 3"/><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 4"/><img src="{pigcms{$static_path}shop/images/3.png">
	                       	</if>
                   		</span>
                  	</div>
                    <div class="status-card">
                        <div class="card-arrow"></div>
                        <div class="card-content">
                            <p class="big">
                            	<if condition="$vo['status'] eq 0"> 任务提交成功<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 1"/> 任务指派成功<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 2"/> 工作人员已受理<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 3"/> 工作人员已处理<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 4"/> 业主已评论<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	</if>
                            </p>
                            <p class="small"> 
          						<if condition="$vo['status'] eq 0"> 
          						<!--span>业主姓名:<strong style="color:red">【{pigcms{$now_user_info.name}】</strong></span><br/>
          						<span>业主电话:<a class="tel-btn" href="tel:{pigcms{$now_user_info.phone}">{pigcms{$now_user_info.phone}</a></span><br/>
          						<span>业主地址:<strong style="color:red">{pigcms{$now_user_info.address}</strong></span><br/-->
          						<span>任务描述:<span style="color:green">{pigcms{$repair_detail.content}</span></span><br/>
          						<elseif condition="$vo['status'] eq 1"/>
          						<span>指派给工作人员:<strong style="color:red">【{pigcms{$worker['name']}】</strong></span><br/>
          						<span>工作人员电话:<a class="tel-btn" href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</a></span><br/>
                            	<elseif condition="$vo['status'] eq 2"/>
          						<span>受理人员姓名:<strong style="color:red">【{pigcms{$worker['name']}】</strong></span><br/>
          						<span>受理人员电话:<a class="tel-btn" href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</a></span><br/>
          						<span>受理描述:<span style="color:green">{pigcms{$repair_detail.msg}</span></span><br/>
                            	<elseif condition="$vo['status'] eq 3"/> 
          						<span>处理人员姓名:<strong style="color:red">【{pigcms{$worker['name']}】</strong></span><br/>
          						<span>处理人员电话:<a class="tel-btn" href="tel:{pigcms{$worker['phone']}">{pigcms{$worker['phone']}</a></span><br/>
          						<span>处理描述:<span style="color:green">{pigcms{$repair_detail.reply_content}</span></span><br/>
                            	
                            	<elseif condition="$vo['status'] eq 4"/> 
          						<span>评分:<strong style="color:red">{pigcms{$repair_detail.score}</strong></span><br/>
          						<span>评论详情:<span style="color:green">{pigcms{$repair_detail.comment}</span></span><br/>
                            	</if>                  
                            </p>
                            <if condition="$vo['status'] eq 0"> 
                            <if condition="$repair_detail['pic']">
                            <div id="map" style="height:150px;">
								<p class="small"><span>任务图片：</span></p>
								<p>
									<ul class="upload_list clearfix">
										<volist name="repair_detail['picArr']" id="vo">
											<li class="upload_item">
												<img src="{pigcms{$config.site_url}/upload/house/{pigcms{$vo}" style="height: 84px; width: 84px;"/>
											</li>
										</volist>
									</ul>
								</p>
                            </div>
                            </if>
                            <elseif condition="$vo['status'] eq 1"/>
                            <elseif condition="$vo['status'] eq 2"/>
                            <elseif condition="$vo['status'] eq 3"/>
                            <if condition="$repair_detail['reply_pic']">
                            <div id="map" style="height:150px;">
								<p class="small"><span>处理图片：</span></p>
								<p>
									<ul class="upload_list clearfix">
										<volist name="repair_detail['reply_picArr']" id="vo">
											<li class="upload_item">
												<img src="{pigcms{$config.site_url}/upload/worker/{pigcms{$vo}" style="height: 84px; width: 84px;"/>
											</li>
										</volist>
									</ul>
								</p>
                            </div>
                            </if>
                            <elseif condition="$vo['status'] eq 4"/>
                            <if condition="$repair_detail['comment_pic']">
                            <div id="map" style="height:150px;">
								<p class="small"><span>评论图片：</span></p>
								<p>
									<ul class="upload_list clearfix">
										<volist name="repair_detail['comment_picArr']" id="vo">
											<li class="upload_item">
												<img src="{pigcms{$config.site_url}/upload/house/{pigcms{$vo}" style="height: 84px; width: 84px;"/>
											</li>
										</volist>
									</ul>
								</p>
                            </div>
                            </if>
                            </if>
                        </div>
                    </div>
                </div>
            </volist>
            </div>
            <if condition="$repair_detail['status'] eq 3">
            
            <div class="time-btm">
                <div class="right-btn" style="margin-left: 30px;">
                    <div class="title none"> <a class="cui-btn active" href="{pigcms{:U('House/village_comment',array('pigcms_id' => $repair_detail['pigcms_id'], 'type' => $type))}" >去评论</a> </div>
                </div>
            </div>
            </if>
        </div>
    </div>
</div>
<div id="fis_elm__6">
    <div id="common-widget-profile" class="common-widget-profile hide">
        <div class="popover">
              <ul class="list-group">
                    <li> <i class="icon-menu"></i> <a href="##">我的订单</a> </li>
                    <li> <i class="icon-location"></i> <a href="##">送货地址管理</a> </li>
                    <li> <i class="icon-favorite"></i> <a href="##">收藏夹</a> </li>
                    <li> <i class="icon-phone"></i> <a href="##">客服电话</a> </li>
                    <li> <i class="icon-coupon"></i> <a href="##">我的代金券</a> </li>
                    <li> <i class="icon-refund"></i> <a href="##">我的退款</a> </li>
                </ul>
        </div>
    </div>
</div>
<div class="global-mask layout"></div>
{pigcms{$shareScript}
<script>
$(document).ready(function(){
	var height = 0;	
	$('.item').each(function(){
		height += $(this).height();
	});
	height -= $('.item:last').height();
	$('.timeline').height(height);
	$('img').click(function(){
		var album_array = [];
		$(this).parents('.upload_list').children('.upload_item').children('img').each(function(l){
			album_array[l] = $(this).attr('src');
		});
		wx.previewImage({
			current:album_array[0],
			urls:album_array
		});
		return false;
	});
});
</script>
</body>
</html>