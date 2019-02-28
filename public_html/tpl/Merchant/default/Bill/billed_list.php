<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active">已对账列表</li>

		</ul>
	</div>
	<style>
	.mainnav_title{
		margin-top:20px;
	}
	.mainnav_title ul a {
		padding: 15px 20px;
	}
	ul, ol {
		margin-bottom: 15px;
	}
	.mainnav_title span{
		color:#7EBAEF;
	}
	.mainnav_title a.on div{
		color:#C1BEBE;
	}
	button{
		padding: 6px;
		background-color: rgba(255, 255, 255, 0);
		box-sizing: border-box;
		border-width: 1px;
		border-style: solid;
		border-color: rgba(121, 121, 121, 1);
		border-radius: 2px;
		-moz-box-shadow: none;
		-webkit-box-shadow: none;
		box-shadow: none;
		font-size: 14px;
		color: #666666;
		cursor: pointer;

	}
	</style>
        <div id="nav" class="mainnav_title">
			<form id="myform" method="post" action="{pigcms{:U('Bill/billed_list')}" style="margin-bottom: 6px;">
                <input type="hidden" name="mer_id" value="{pigcms{$mer_id}">
                <input type="hidden" name="type" value="{pigcms{$type}">
                <div style="float:left"><font color="#000">时间筛选 ：</font></div>
                <input type="text" class="input fl" name="begin_time" style="width:120px;height:46px;" id="d4311"  value="{pigcms{$begin_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
                <input type="text" class="input fl" name="end_time" style="width:120px;height:46px;" id="d4311" value="{pigcms{$end_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;&nbsp;
                <input type="submit" value="筛选" style="position:absolute;width:52px;">
            </form>
		</div>
        <div id="nav" class="mainnav_title" style="margin-top:8px">
                <ul>
					<if condition="$bill_time.meal_time gt 0"><a href="{pigcms{:U('Bill/billed_list',array('mer_id'=>$mer_id, 'type' => 'meal'))}" <if condition="$type eq 'meal'">class="on"</if>>{pigcms{$config.meal_alias_name}账单</a></if>
					<if condition="$bill_time.group_time gt 0"><a href="{pigcms{:U('Bill/billed_list',array('mer_id'=>$mer_id, 'type' => 'group'))}" <if condition="$type eq 'group'">class="on"</if>>{pigcms{$config.group_alias_name}账单</a></if>
					<php>if($bill_time['appoint_time']>0){</php><if condition="$config['appoint_page_row'] gt 0">
						<a href="{pigcms{:U('Bill/billed_list',array('mer_id'=>$mer_id, 'type' => 'appoint'))}" <if condition="$type eq 'appoint'">class="on"</if>>{pigcms{$config.appoint_alias_name}账单</a>
						</if><php>}</php>
					<php>if($bill_time['waimai_time']>0){</php><if condition="$config['waimai_alias_name']">
						<a href="{pigcms{:U('Bill/billed_list',array('mer_id'=>$mer_id, 'type' => 'waimai'))}" <if condition="$type eq 'waimai'">class="on"</if>>{pigcms{$config.waimai_alias_name}账单</a>
					</if><php>}</php>
					<if condition="$bill_time.shop_time gt 0"><a href="{pigcms{:U('Bill/billed_list',array('mer_id'=>$mer_id, 'type' => 'shop'))}" <if condition="$type eq 'shop'">class="on"</if>>{pigcms{$config.shop_alias_name}账单</a></if>
					<php>if($bill_time['store_time']>0){</php><if condition="$config['is_cashier'] OR $config['pay_in_store']">
					<a href="{pigcms{:U('Bill/billed_list',array('mer_id'=>$mer_id, 'type' => 'store'))}" <if condition="$type eq 'store'">class="on"</if>>到店付账单</a>
					</if><php>}</php>
					<php>if($bill_time['weidian_time']>0){</php><if condition="$config['is_open_weidian']">
						<a href="{pigcms{:U('Bill/billed_list',array('mer_id'=>$mer_id, 'type' => 'weidian'))}" <if condition="$type eq 'weidian'">class="on"</if>>微店账单</a>
					</if><php>}</php>
					<php>if($bill_time['wxapp_time']>0){</php>
						<if condition="$config['wxapp_url']">
							<a href="{pigcms{:U('Bill/billed_list',array('mer_id'=>$mer_id, 'type' => 'wxapp'))}" <if condition="$type eq 'wxapp'">class="on"</if>>营销账单</a>
						</if>
					<php>}</php>
				</ul>
        </div>
	
        <style type="text/css">
            .mainnav_title {line-height:40px;/* height:40px; */border-bottom:1px solid #eee;color:#31708f;}
            .mainnav_title a {color:#004499;margin:0 5px;padding:4px 7px;background:#d9edf7;}
            .mainnav_title a:hover ,.mainnav_title a.on{background:#498CD0;color:#fff;text-decoration: none;}
        </style>
      

	
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					<div class="tabbable" >
								<div class="row">					
									<div class="col-xs-12">		
										<div class="grid-view">
										<div><span style="color:#000;">未对账订单数量：<font color="red">{pigcms{$un_bill_count}</font></span></div>
											<table class="table table-striped table-bordered table-hover">
												<thead>
													
													<tr>
														<th>对账时间</th>
														<th>对账总金额</th>
														<th>对账总订单数</th>
														<th>操作</th>
													</tr>
												</thead>
												<tbody>
													<if condition="$bill_list">
														<volist name="bill_list" id="vo">
															<tr>
																<td>{pigcms{$vo.bill_time|date="Y/m/d H:i",###}</td>
																<td>{pigcms{$vo['money']/100}元</td>
																<td>{pigcms{:count($vo['id_list'])}个</td>
																<td><a href="{pigcms{:U('Bill/billed_info',array('id'=>$vo['id']))}"><button>查看</button></a></td>
															</tr>
														</volist>
													
														<tr class="odd">
															<td colspan="19" id="show_count"></td>
														</tr>
														<tr><td class="textcenter pagebar" colspan="19">{pigcms{$pagebar}</td></tr>	
													<else/>
														<tr class="odd"><td class="textcenter red" colspan="17" >该的店铺暂时还没有订单。</td></tr>
													</if>
												
														
														
												
												</tbody>
											</table>
										</div>						
									</div>
									<!--div class="col-xs-2" style="margin-top: 15px;">
										<a class="btn btn-success" href="#">导出成excel</a>
									</div-->
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$(function(){
	$('.handle_btn').live('click',function(){
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'操作订单',
			padding: 0,
			width: 720,
			height: 520,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		return false;
	});
	
	$('#group_id').change(function(){
		$('#frmselect').submit();
	});
	
});




</script>
<include file="Public:footer"/>
