<include file="Public:header"/>
<style>
	img{height:30px;width:60px;}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Coupon/index')}">平台优惠券列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Coupon/add')}','添加平台优惠券',800,500,true,false,false,addbtn,'edit',true);">添加平台优惠券</a>
					<a href="{pigcms{:U('Coupon/had_pull')}" class="on">领取列表</a>
					<a href="{pigcms{:U('Coupon/send_coupon')}" >派发优惠券</a>				
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Coupon/had_pull')}" method="get">
							<input type="hidden" name="c" value="Coupon"/>
							<input type="hidden" name="a" value="had_pull"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>优惠券标题</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>用户昵称</option>
							</select>
							<input type="submit" value="查询" class="button"/>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
						
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>优惠券名称</th>
								<th>用户名</th>
							
								<th>数量</th>
								<th>领取时间</th>
								<th class="textcenter">状态</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($coupon_list)">
								<volist name="coupon_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.nickname}</td>
										
										<td>{pigcms{$vo.num}</td>
										<td>{pigcms{$vo.receive_time|date='Y-m-d',###}</td>
										<td class="textcenter"><if condition="$vo['is_use'] eq 1"><font color="green">已使用</font><elseif condition="$vo['is_use'] eq 0" /><font color="red">未使用</font><else /><font color="red">待审核</font></if></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="7">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('#indexsort_edit_btn').click(function(){
			$(this).prop('disabled',true).html('提交中...');
			$.post("/merchant.php?g=Merchant&c=Config&a=merchant_indexsort",{group_indexsort:$('#group_indexsort').val(),indexsort_groupid:$('#indexsort_groupid').val()},function(result){
				alert('处理完成！正在刷新页面。');
				window.location.href = window.location.href;
			});
		});
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
	});
	
</script>
<include file="Public:footer"/>