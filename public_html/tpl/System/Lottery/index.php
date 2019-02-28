<include file="Public:header"/>
<style>
	img{height:30px;width:60px;}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Lottery/index')}" class="on">分享抽奖</a>
					<a href="{pigcms{:U('Lottery/had_pull')}" >领取列表</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Lottery/index')}" method="get">
							<input type="hidden" name="c" value="Lottery"/>
							<input type="hidden" name="a" value="index"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>标题</option>
								<option value="Lottery_id" <if condition="$_GET['searchtype'] eq 'Lottery_id'">selected="selected"</if>>消息ID</option>
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
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>商家ID</th>
								<th>商家名称</th>
		
								<th>添加时间</th>
								<th class="textcenter">状态</th>
								<th class="textcenter">编辑</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($lottery_list)">
								<volist name="lottery_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.mer_id}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.add_time|date='Y-m-d',###} </td>
										<td class="textcenter"><if condition="$vo['status'] eq 0"><font color="blue">待审核</font><elseif condition="$vo['status'] eq 1"/><font color="green">启用</font><elseif condition="$vo['status'] eq 2"/><font color="blue">禁止</font><elseif condition="$vo['status'] eq 3 " /><font color="black">商家关闭</font><else /><font color="red">系统关闭</font></if></td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Lottery/edit',array('id'=>$vo['id']))}','编辑抽奖信息',900,500,true,false,false,editbtn,'edit',true);">编辑</a></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="14">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="14">列表为空！</td></tr>
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