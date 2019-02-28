<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Merchant_money/index')}">商家余额</a></li>
			<li class="active">提现记录</li>

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
       
     
	
        <style type="text/css">
            .mainnav_title {line-height:40px;/* height:40px; */border-bottom:1px solid #eee;color:#31708f;}
            .mainnav_title a {color:#004499;margin:0 5px;padding:4px 7px;background:#d9edf7;}
            .mainnav_title a:hover ,.mainnav_title a.on{background:#498CD0;color:#fff;text-decoration: none;}
        </style>
      

	
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
			<select id="withdraw_type" name="withdraw_type" >			
				<option value="1" <if condition="$_GET['type'] eq 1" >selected="selected"</if>>提现至微信</option>
				<option value="2" <if condition="$_GET['type'] eq 2" >selected="selected"</if>>提现至第三方</option>
				
			</select>
			<a class="btn btn-success"  href="{pigcms{:U('Bill/export',array('type'=>'withdraw','mer_id'=>$merchant_session['mer_id']))}&withdraw_type={pigcms{$_GET['type']|default=1}">导出</a>
			<a class="btn btn-success"  href="{pigcms{:U('Merchant_money/bank_list')}">银行卡管理</a>
				<div class="col-sm-12">
					<div class="tabbable" >
								<div class="row">					
									<div class="col-xs-12">		
										<div class="grid-view">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>提现时间</th>
														<th>提现金额</th>
														<th>状态</th>
														<th>备注</th>
													</tr>
												</thead>
												<tbody>
													<if condition="$withdraw_list">
														<volist name="withdraw_list" id="vo">
															<tr>
																<td>{pigcms{$vo.withdraw_time|date="Y/m/d H:i",###}</td>
																<td>{pigcms{$vo['money']/100}元</td>
																<td>
																	<if condition="$vo.status eq 0">
																		<font color="red">审核中...</font>
																	<elseif condition="$vo.status eq 1" />
																		<font color="green">已通过</font>
																	<elseif condition="$vo.status eq 2 OR $vo.status eq 4" />
																		<font color="red">被驳回</font>
																	<elseif condition="$vo.status eq 3" />
																		<font color="green">已提现</font>
																	</if>
																	
																</td>
																<td><if condition="$vo.desc neq ''"> {pigcms{$pay_type[$vo['pay_type']]}| {pigcms{$vo.desc} |</if>{pigcms{$vo.remark}</td>
																
															</tr>
														</volist>
													
														<tr class="odd">
															<td colspan="4" id="show_count"></td>
														</tr>
														<tr><td class="textcenter pagebar" colspan="4">{pigcms{$pagebar}</td></tr>	
													<else/>
														<tr class="odd"><td class="textcenter red" colspan="17" >没有提现记录</td></tr>
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
	
	$('#withdraw_type').change(function(){
		window.location.href="{pigcms{:U('withdraw_list')}&type="+$(this).val();
	});
	
});




</script>
<include file="Public:footer"/>
