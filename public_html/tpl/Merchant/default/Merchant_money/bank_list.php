<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active"><a href="<if condition="$config.open_allinyun eq 1">{pigcms{:U('SetAccountDeposit/index')}<else />{pigcms{:U('Merchant_money/index')}</if>"><if condition="$config.open_allinyun eq 1">云商通设置<else />商家余额</if></a></li>
			<li class="active">银行卡列表</li>

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
	.all{
		border-collapse:collapse;
		border:none;
	}
	.all td{
		border:solid #000 1px;
		border-color:"#cccc99";
		height: 20px;
		text-align: center;
	}
	.all th{
		border:solid #000 1px;
		border-color:"#cccc99";
		height: 20px;
	}
	button{
		padding: 6px;
		background-color: rgb(241, 235, 235);;
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
	
	#myform div{
		margin-top:10px;
	}
	</style>
        

        <div style="margin:10px;">
			
				
           
        </div>

	
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
				<button class="btn btn-success" onclick="add()" style="float:left;margin-bottom: 8px;">添加银行卡</button>
					<div class="tabbable" >
								<div class="row">					
									<div class="col-xs-13">		
										<div class="grid-view">
	
											<table class="table table-striped table-bordered table-hover">
												<thead>
													
													<tr>
												
														<th>ID</th>
														<th>开户账号</th>
														<th>开户名</th>
														<th>所在银行</th>
														<th>默认</th>
										
														<th>创建时间</th>
														
														<th>操作</th>
													</tr>
												</thead>
												<tbody>
													<if condition="$bank_list">
														<volist name="bank_list" id="vo">
															<tr>
													
																<td>{pigcms{$vo.id}</td>
																<td>{pigcms{$vo.account}</td>
																<td>{pigcms{$vo.account_name}</td>
																<td>{pigcms{$vo.remark}</td>
																<td><if condition="$vo.is_default eq 1">是<else />否</if></td>
																<td>{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</td>
																<td>
																	<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('create_bank_account',array('bank_id'=>$vo['id']))}">
																		<i class="ace-icon fa fa-pencil bigger-130"></i>
																	</a>
																	<a title="删除" class="red delete" style="padding-right:8px;" href="{pigcms{:U('delete_bank_account',array('bank_id'=>$vo['id']))}">
																		<i class="ace-icon fa fa-trash-o bigger-130"></i>
																	</a>
																</td>
															</tr>
														</volist>
												
														
														<tr class="odd">
															<td colspan="13" id="show_count"></td>
														</tr>
														
													<else />
														<tr class="odd"><td class="textcenter red" colspan="13" >暂时还没有收入记录</td></tr>
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
	
	jQuery(document).on('click','a.delete',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
});
function add(){
	window.location.href='<if condition="$config.open_allinyun eq 1">{pigcms{:U('SetAccountDeposit/addBank')}<else />{pigcms{:U('create_bank_account')}</if>'
}
// function exports(){
	// var order_type = $('select[name="order_type"]').val();
	// var order_id = $('input[name="order_id"]').val();
	// var begin_time = $('input[name="begin_time"]').val();
	// var end_time = $('input[name="end_time"]').val();
	// var store_id = $('#store_id').val();
	// if(order_type=='all'&&order_id!=''){
		// alert('该分类下没有不能填订单ID');
	// }else{
		// var export_url ="{pigcms{:U('Bill/export',array('mer_id'=>$mer_id, 'type' => 'income'))}&order_type="+order_type+'&order_id='+order_id+'&begin_time='+begin_time+'&end_time='+end_time+'&store_id='+store_id;
		// window.location.href = export_url;
	// }
// }

		 var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('Bill/export')}"

</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
<include file="Public:footer"/>
