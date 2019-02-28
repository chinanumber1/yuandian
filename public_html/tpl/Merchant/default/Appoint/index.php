<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Appoint/index')}">预约管理</a>
			</li>
			<li class="active">预约列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				#shopList .table-striped{table-layout:fixed;word-break:keep-all;             /* 不换行 */
white-space:nowrap;            /* 不换行 */
overflow:hidden;                  /* 内容超出宽度时隐藏超出部分的内容 */
text-overflow:ellipsis;  }
				#shopList .table-striped th,#shopList .table-striped td{width:100%;
         /* 不换行 */
white-space:nowrap;            /* 不换行 */
overflow:hidden;                  /* 内容超出宽度时隐藏超出部分的内容 */
text-overflow:ellipsis;            /* 当对象内文本溢出时显示省略标记(...) ；需与overflow:hidden;一起使用。*/}

#shopList .table-striped td:last-child{text-overflow:clip; text-align:center; width:100%}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<button class="btn btn-success" onclick="CreateShop()">添加预约</button>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="80">编号</th>
									<th>服务名称</th>
									<!--th>开始时间</th>
									<th>结束时间</th-->
									<if condition="!C('butt_open')">
										<th>创建时间</th>
										<th>分类名称</th>
										<th>查看二维码</th>
									</if>
									<th>定金状态</th>
									<th>定金金额</th>
									<th>活动状态</th>
									<if condition="!C('butt_open')"><th>审核状态</th></if>
									<th style="text-align:center;width:150px;">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$appoint_list">
									<volist name="appoint_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.appoint_id}</td>
											<!--<td><a href="{pigcms{$config.site_url}/index.php?g=Appoint&c=Detail&appoint_id={pigcms{$vo.appoint_id}" target="_blank" data-title="{pigcms{$vo.appoint_name}" data-pic="{pigcms{$vo.list_pic}" class="appoint_name">{pigcms{$vo.appoint_name}</a></td>-->
											<td>{pigcms{$vo.appoint_name}</td>
											<!--td>{pigcms{$vo.start_time|date='Y-m-d',###}</td>
											<td>{pigcms{$vo.end_time|date='Y-m-d',###}</td-->
											<if condition="!C('butt_open')">
											<td>{pigcms{$vo.create_time|date='Y-m-d H:i:s',###}</td>		
											<td>{pigcms{$vo.category_name}&nbsp;&nbsp;<if condition='$vo["is_autotrophic"]==1'>(<span class="green">平台自营</span>)</if></td>
											<td><if condition='$vo["is_autotrophic"]==0'><a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=appoint&id={pigcms{$vo.appoint_id}&img=1" class="see_qrcode">查看二维码</a><else/>无</if></td>
											</if>
											<td>
												<if condition="$vo['payment_status'] eq 0"><span style="color:green">不收定金</span>
												<elseif condition="$vo['payment_status'] eq 1" /><span style="color:red">收定金</span>
												</if>
											</td>
											<td>￥{pigcms{$vo.payment_money}</td>
											<td>
												<if condition="$vo['appoint_status'] eq 0"><span style="color:green">开启</span>
												<elseif condition="$vo['appoint_status'] eq 1" /><span style="color:red">关闭</span>
												</if>
											</td>
											<if condition="!C('butt_open')">
											<td>
												<if condition="$vo['check_status'] eq 0"><span style="color:red">待审核</span>
												<elseif condition="$vo['check_status'] eq 1" /><span style="color:green">通过</span>
												</if>
											</td>
											</if>
											<td style="text-align:center;">
												<a class="label label-sm label-info" title="订单列表" href="{pigcms{:U('Appoint/order_list',array('appoint_id'=>$vo['appoint_id']))}">订单列表</a>
												<a class="label label-sm label-info" title="编辑" href="{pigcms{:U('Appoint/frame_edit',array('appoint_id'=>$vo['appoint_id'],'is_show'=>1))}">编辑</a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="10" >您没有添加过预约！</td></tr>
								</if>
							</tbody>
						</table>
						{pigcms{$pagebar}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	function CreateShop(){
		window.location.href = "{pigcms{:U('Appoint/add')}";
	}
	
	$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看渠道二维码',
				padding: 0,
				width: 430,
				height: 433,
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
</script>
<include file="Public:footer"/>
