<include file="Public:header"/>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Coupon/index')}">平台优惠券列表</a>
			
			<a href="{pigcms{:U('Coupon/send_coupon')}"  class="on" >派发优惠券</a>
		</ul>
	</div>
	<div id="nav" class="mainnav_title" style="margin-top:5px;">
		<ul  id="myTab">
			<a href="{pigcms{:U('send_coupon')}"  >
				等级派发
			</a>
			<a href="{pigcms{:U('send_all')}" >
				全部派发
			</a>
			<a data-toggle="tab" href="{pigcms{:U('send_person')}"  >
				个人派发
			</a>
			<a href="{pigcms{:U('weixin_send')}" class="on">
				微信购买派发
			</a>
			<a href="{pigcms{:U('send_history')}" >
				派发记录
			</a>
		</ul>
	</div>
	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			
			<div class="tab-content">
				
				<div id="groupinfo" class="tab-pane active"  >
					<div class="widget-box">
						<div class="widget-body" id="group_main" style="padding:20px;height:100px;width:100%;">
						
							微信购买金额设置：<input type="text" name="money" style="padding: 3px 3px 4px;" value="{pigcms{$config.weixin_send_money}"> 元(请填写大于等于0.01的数字)
						</div>
						
					</div>	
					<div class="row">					
						<div class="col-xs-12">		
							<div class="grid-view">
								
							</div>						
						</div>
					
					</div>
				</div>
				<style type="text/css">
					.radio {
						min-height: 27px;
					}
				</style>
				<div class="tab-pane " style="display:block">
					<div class="widget-box">
						
					
						
						<div class="widget-body form-group" id="coupon_list" style="padding:20px;height:300px;width:100%;line-height: 1.5;overflow:auto">
							<table class="table table-striped table-bordered table-hover" id="user_table" >
								<thead>
									<tr>
										<th>勾选优惠券</th>
										
										<th>优惠券名称</th>
										<th>优惠券描述</th>
										
									</tr>
								</thead>
								<tbody id="user_list">
							<volist name="coupon_list" id="vo">
								
								
									<tr class="even"><td style="width: 120px"><input type="checkbox" name="coupon_id[]" value="{pigcms{$vo.coupon_id}" id="coupon{pigcms{$vo.id}" <if condition="in_array($vo['coupon_id'],explode(',',$config['weixin_send_coupon_list']))">checked="checked" </if>></td><td style="width: 120px">{pigcms{$vo.name}</td><td style="width: 120px">{pigcms{$vo.des}</td></tr>
							</volist>
							</tbody>
							</table>
						</div>
					</div>	
					<div class="row">					
						<div class="col-xs-12" style="    margin-bottom: 8px;">		
							<div class="grid-view">
								<button class="btn btn-info" type="submit" id="save_btn" style="margin-left: 25%;" href="">
									<i class="ace-icon fa fa-check bigger-110"></i>
									保存
								</button>
							</div>						
						</div>
					
					</div>
				</div>
				
			</div>
		</div>
	</form>
</div>
<link rel="stylesheet" href="{pigcms{$static_path}css/bootstrap.min.css">
	
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>

</script>


<include file="Public:footer"/>
