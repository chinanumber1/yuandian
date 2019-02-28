<include file="Public:header"/>
<style>
	img{height:30px;width:60px;}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Lottery/index')}" >分享抽奖</a>
					<a href="{pigcms{:U('Lottery/had_pull')}" class="on" >领取列表</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Lottery/had_pull')}" method="get">
							<input type="hidden" name="c" value="Lottery"/>
							<input type="hidden" name="a" value="had_pull"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="id" <if condition="$_GET['searchtype'] eq 'id'">selected="selected"</if>>用户ID</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>用户手机</option>
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
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>商家ID</th>
								<th>用户ID</th>
								<th>用户手机</th>
								<th>分享时间</th>
								<th>中奖时间</th>
								<th>描述</th>
								
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($lottery_list)">
								<volist name="lottery_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.mer_id}</td>
										<td>{pigcms{$vo.uid}<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','编辑用户信息',680,560,true,false,false,editbtn,'edit',true);">查看用户信息</a></td>
										<td>{pigcms{$vo.phone}</td>
										<td>{pigcms{$vo.lottery_time|date='Y-m-d',###} </td>
										<td><if condition="$vo.award_time gt 0">{pigcms{$vo.award_time|date='Y-m-d',###} </if></td>
										<php>$tmp = unserialize($vo['return']);</php>
										<td>{pigcms{$tmp.msg} </td>
										
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